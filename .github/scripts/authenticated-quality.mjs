import fs from 'node:fs';
import path from 'node:path';
import { spawn } from 'node:child_process';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const baseUrl = (argMap['--base-url'] || 'http://127.0.0.1:8080').replace(/\/$/, '');
const configPath = path.resolve(process.cwd(), argMap['--config'] || '.github/scripts/internal-pages.json');
const outputDir = path.resolve(process.cwd(), argMap['--output-dir'] || '.tmp/authenticated-quality');
const summaryPath = path.resolve(process.cwd(), argMap['--summary-json'] || path.join(outputDir, 'summary.json'));
const stepSummaryPath = argMap['--step-summary'] || '';

fs.mkdirSync(outputDir, { recursive: true });
fs.mkdirSync(path.join(outputDir, 'lighthouse'), { recursive: true });
fs.mkdirSync(path.join(outputDir, 'pa11y'), { recursive: true });
fs.mkdirSync(path.join(outputDir, 'headers'), { recursive: true });

const profiles = JSON.parse(fs.readFileSync(configPath, 'utf8'));

function collectSetCookies(headers) {
    if (!headers) {
        return [];
    }

    if (typeof headers.getSetCookie === 'function') {
        const cookies = headers.getSetCookie().filter(Boolean);
        if (cookies.length > 0) {
            return cookies;
        }
    }

    const singleHeader = typeof headers.get === 'function'
        ? headers.get('set-cookie')
        : null;

    if (!singleHeader) {
        return [];
    }

    return singleHeader
        .split(/,(?=\s*[^;,=\s]+=[^;,]+)/)
        .map((item) => item.trim())
        .filter(Boolean);
}

function updateCookieJar(cookieJar, response) {
    const setCookies = collectSetCookies(response.headers);

    for (const item of setCookies) {
        const [pair] = item.split(';');
        const separatorIndex = pair.indexOf('=');
        if (separatorIndex <= 0) {
            continue;
        }

        const name = pair.slice(0, separatorIndex).trim();
        const value = pair.slice(separatorIndex + 1).trim();
        cookieJar.set(name, value);
    }
}

function cookieHeader(cookieJar) {
    return [...cookieJar.entries()]
        .map(([name, value]) => `${name}=${value}`)
        .join('; ');
}

function extractCsrfToken(html) {
    const match = html.match(/name="csrf_token"\s+value="([^"]+)"/);
    return match ? match[1] : null;
}

function runCommand(command, args, options = {}) {
    return new Promise((resolve) => {
        let settled = false;
        const finish = (result) => {
            if (!settled) {
                settled = true;
                resolve(result);
            }
        };

        const child = spawn(command, args, {
            cwd: process.cwd(),
            env: process.env,
            stdio: ['ignore', 'pipe', 'pipe'],
            ...options,
        });

        let stdout = '';
        let stderr = '';

        child.stdout.on('data', (chunk) => {
            stdout += chunk.toString();
        });

        child.stderr.on('data', (chunk) => {
            stderr += chunk.toString();
        });

        child.on('error', (error) => {
            finish({
                code: 1,
                stdout,
                stderr: `${stderr}${error.message}\n`,
            });
        });

        child.on('close', (code) => {
            finish({ code: code ?? 1, stdout, stderr });
        });
    });
}

async function loginProfile(profile) {
    const jar = new Map();
    const loginPageResponse = await fetch(`${baseUrl}/login.php`, {
        redirect: 'manual',
    });

    if (!loginPageResponse.ok) {
        throw new Error(`Impossibile aprire login.php per ${profile.role}: HTTP ${loginPageResponse.status}`);
    }

    updateCookieJar(jar, loginPageResponse);
    const loginHtml = await loginPageResponse.text();
    const csrfToken = extractCsrfToken(loginHtml);

    if (!csrfToken) {
        throw new Error(`Token CSRF non trovato per ${profile.role}`);
    }

    const formData = new URLSearchParams({
        csrf_token: csrfToken,
        identifier: profile.identifier,
        password: profile.password,
    });

    const loginResponse = await fetch(`${baseUrl}/login.php`, {
        method: 'POST',
        redirect: 'manual',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            Cookie: cookieHeader(jar),
        },
        body: formData.toString(),
    });

    updateCookieJar(jar, loginResponse);

    if (![302, 303].includes(loginResponse.status)) {
        const body = await loginResponse.text();
        throw new Error(`Login fallito per ${profile.role}: HTTP ${loginResponse.status} ${body.slice(0, 180)}`);
    }

    const location = loginResponse.headers.get('location') || '';
    if (!location.includes('area_personale.php')) {
        throw new Error(`Redirect inatteso dopo login per ${profile.role}: ${location || 'nessuno'}`);
    }

    const cookie = cookieHeader(jar);
    if (jar.size === 0 || cookie === '') {
        throw new Error(`Cookie di sessione non trovato per ${profile.role}`);
    }

    return cookie;
}

async function checkAuthenticatedPage(url, cookie) {
    const response = await fetch(url, {
        redirect: 'manual',
        headers: {
            Cookie: cookie,
        },
    });

    if (response.status !== 200) {
        return {
            ok: false,
            status: response.status,
            location: response.headers.get('location') || '',
        };
    }

    const html = await response.text();
    if (html.includes('Accedi al tuo account') && html.includes('name="identifier"')) {
        return {
            ok: false,
            status: 200,
            location: 'login-form-detected',
        };
    }

    return { ok: true, status: 200, location: '' };
}

const results = [];

for (const profile of profiles) {
    try {
        const cookie = await loginProfile(profile);
        const headersPath = path.join(outputDir, 'headers', `${profile.role}.json`);
        fs.writeFileSync(headersPath, `${JSON.stringify({ Cookie: cookie }, null, 2)}\n`);

        for (const page of profile.pages) {
            const url = `${baseUrl}/${page}`;
            const result = {
                role: profile.role,
                page,
                auth: 'ok',
                lighthouse: null,
                pa11yIssues: null,
                status: 'pass',
                focusAreas: [],
            };

            const access = await checkAuthenticatedPage(url, cookie);
            if (!access.ok) {
                result.auth = `fail (${access.status}${access.location ? `, ${access.location}` : ''})`;
                result.status = 'fail';
                result.focusAreas.push('Accesso autenticato non riuscito');
                results.push(result);
                continue;
            }

            const lighthouseReport = path.join(outputDir, 'lighthouse', `${profile.role}-${page.replace(/\.php$/, '')}.json`);
            const lighthouseRun = await runCommand('lighthouse', [
                url,
                '--output=json',
                `--output-path=${lighthouseReport}`,
                '--quiet',
                `--extra-headers=${headersPath}`,
                '--chrome-flags=--headless --no-sandbox --disable-dev-shm-usage',
            ]);

            if (lighthouseRun.code === 0 && fs.existsSync(lighthouseReport)) {
                const report = JSON.parse(fs.readFileSync(lighthouseReport, 'utf8'));
                result.lighthouse = {
                    performance: Math.round((report.categories?.performance?.score || 0) * 100),
                    accessibility: Math.round((report.categories?.accessibility?.score || 0) * 100),
                    bestPractices: Math.round((report.categories?.['best-practices']?.score || 0) * 100),
                    seo: Math.round((report.categories?.seo?.score || 0) * 100),
                };

                const failingAudits = Object.values(report.audits || {})
                    .filter((audit) => {
                        const mode = audit?.scoreDisplayMode;
                        if (mode === 'notApplicable' || mode === 'manual' || mode === 'informative') {
                            return false;
                        }

                        return audit?.score !== null && typeof audit?.score === 'number' && audit.score < 0.9;
                    })
                    .sort((a, b) => (a.score ?? 1) - (b.score ?? 1))
                    .slice(0, 3);

                for (const audit of failingAudits) {
                    result.focusAreas.push(`LH: ${audit.title}`);
                }

                if (Object.values(result.lighthouse).some((score) => score < 90)) {
                    result.status = 'warn';
                }
            } else {
                result.status = 'fail';
                result.focusAreas.push('Lighthouse non eseguito correttamente');
            }

            const pa11yReport = path.join(outputDir, 'pa11y', `${profile.role}-${page.replace(/\.php$/, '')}.json`);
            const pa11yConfigPath = path.join(outputDir, 'pa11y', `${profile.role}-config.json`);
            fs.writeFileSync(pa11yConfigPath, `${JSON.stringify({
                chromeLaunchConfig: {
                    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
                },
                standard: 'WCAG2AA',
                headers: {
                    Cookie: cookie,
                },
            }, null, 2)}\n`);

            const pa11yRun = await runCommand('pa11y', [
                '--config',
                pa11yConfigPath,
                '--reporter',
                'json',
                url,
            ]);

            fs.writeFileSync(pa11yReport, pa11yRun.stdout || '[]');

            if (fs.existsSync(pa11yReport)) {
                const issues = JSON.parse(fs.readFileSync(pa11yReport, 'utf8') || '[]');
                result.pa11yIssues = Array.isArray(issues) ? issues.length : 0;
                if (Array.isArray(issues)) {
                    for (const issue of issues.slice(0, 3)) {
                        result.focusAreas.push(`Pa11y: ${issue.message}`);
                    }
                }

                if ((result.pa11yIssues || 0) > 0 && result.status !== 'fail') {
                    result.status = 'warn';
                }
            } else {
                result.status = 'fail';
                result.focusAreas.push('Pa11y non eseguito correttamente');
            }

            if (result.focusAreas.length === 0) {
                result.focusAreas.push('Nessun problema prioritario rilevato');
            }

            results.push(result);
        }
    } catch (error) {
        results.push({
            role: profile.role,
            page: '(login)',
            auth: `fail (${error.message})`,
            lighthouse: null,
            pa11yIssues: null,
            status: 'fail',
            focusAreas: ['Login CI da sistemare'],
        });
    }
}

const statusIcon = {
    pass: '✅',
    warn: '⚠️',
    fail: '❌',
};

const summary = {
    generatedAt: new Date().toISOString(),
    runUrl: process.env.GITHUB_RUN_ID
        ? `${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}/actions/runs/${process.env.GITHUB_RUN_ID}`
        : null,
    results,
};

fs.writeFileSync(summaryPath, `${JSON.stringify(summary, null, 2)}\n`);

let markdown = '## Authenticated internal pages\n\n';
markdown += '| Ruolo | Pagina | Auth | Lighthouse | Pa11y issue | Stato |\n';
markdown += '| --- | --- | --- | --- | ---: | --- |\n';

for (const result of results) {
    const lighthouseText = result.lighthouse
        ? `P ${result.lighthouse.performance} / A ${result.lighthouse.accessibility} / BP ${result.lighthouse.bestPractices} / SEO ${result.lighthouse.seo}`
        : 'n/d';
    markdown += `| \`${result.role}\` | \`${result.page}\` | ${result.auth} | ${lighthouseText} | ${result.pa11yIssues ?? 'n/d'} | ${statusIcon[result.status]} |\n`;
}

markdown += '\n### Cose da sistemare prima\n\n';
const warnings = results.filter((result) => result.status !== 'pass');
if (warnings.length === 0) {
    markdown += '- Nessun problema rilevato nel layer autenticato.\n';
} else {
    for (const item of warnings) {
        markdown += `- **${item.role} / ${item.page}**: ${item.focusAreas.join(' | ')}\n`;
    }
}

if (stepSummaryPath) {
    fs.appendFileSync(stepSummaryPath, `${markdown}\n`);
}

process.stdout.write(`${markdown}\n`);

if (results.some((result) => result.status === 'fail' || (result.pa11yIssues ?? 0) > 0 || Object.values(result.lighthouse || {}).some((score) => score < 90))) {
    process.exitCode = 1;
}
