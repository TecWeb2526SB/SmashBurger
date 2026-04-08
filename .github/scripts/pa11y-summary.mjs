import fs from 'node:fs';
import path from 'node:path';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const reportsDir = path.resolve(process.cwd(), argMap['--reports-dir'] || '.tmp/pa11y');
const pagesFile = path.resolve(process.cwd(), argMap['--pages-file'] || '.tmp/public-pages.txt');
const outputJsonPath = path.resolve(process.cwd(), argMap['--output-json'] || path.join(reportsDir, 'summary.json'));
const stepSummaryPath = argMap['--step-summary'] || '';

const pages = fs.readFileSync(pagesFile, 'utf8')
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean);

const messageMap = new Map();
const pageResults = [];

for (const page of pages) {
    const slug = page.replace(/\.php$/, '');
    const reportPath = path.join(reportsDir, `${slug}.json`);

    if (!fs.existsSync(reportPath)) {
        pageResults.push({ page, issues: -1, status: 'missing' });
        continue;
    }

    const issues = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
    const issueCount = Array.isArray(issues) ? issues.length : 0;

    if (Array.isArray(issues)) {
        for (const issue of issues.slice(0, 20)) {
            const key = issue.message || 'Issue senza messaggio';
            const current = messageMap.get(key) || {
                message: key,
                count: 0,
                pages: new Set(),
            };
            current.count += 1;
            current.pages.add(page);
            messageMap.set(key, current);
        }
    }

    pageResults.push({
        page,
        issues: issueCount,
        status: issueCount === 0 ? 'pass' : 'warn',
    });
}

const issues = [...messageMap.values()]
    .map((issue) => ({ ...issue, pages: [...issue.pages] }))
    .sort((a, b) => b.count - a.count)
    .slice(0, 8);

const missingPages = pageResults.filter((page) => page.status === 'missing').length;
const pagesWithIssues = pageResults.filter((page) => page.status === 'warn').length;
const totalIssues = pageResults.reduce((sum, page) => sum + Math.max(page.issues, 0), 0);
const runUrl = process.env.GITHUB_RUN_ID
    ? `${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}/actions/runs/${process.env.GITHUB_RUN_ID}`
    : null;

const summary = {
    generatedAt: new Date().toISOString(),
    runUrl,
    totalPages: pageResults.length,
    missingPages,
    pagesWithIssues,
    totalIssues,
    pages: pageResults,
    issues,
};

fs.mkdirSync(path.dirname(outputJsonPath), { recursive: true });
fs.writeFileSync(outputJsonPath, `${JSON.stringify(summary, null, 2)}\n`);

const statusIcon = {
    pass: '✅',
    warn: '⚠️',
    missing: '❌',
};

let markdown = '## Accessibility overview (Pa11y)\n\n';
markdown += `- Pagine analizzate: **${pageResults.length - missingPages}/${pageResults.length}**\n`;
markdown += `- Pagine con issue: **${pagesWithIssues}**\n`;
markdown += `- Issue totali rilevate: **${totalIssues}**\n`;
if (runUrl) {
    markdown += `- Run GitHub: [apri il dettaglio](${runUrl})\n`;
}
markdown += '\n';
markdown += '| Pagina | Issue | Stato |\n';
markdown += '| --- | ---: | --- |\n';

for (const result of pageResults) {
    markdown += `| \`${result.page}\` | ${result.issues >= 0 ? result.issues : 'n/d'} | ${statusIcon[result.status]} |\n`;
}

markdown += '\n### Cose da sistemare prima\n\n';
if (issues.length === 0) {
    markdown += '- Nessuna issue Pa11y rilevata nelle pagine analizzate.\n';
} else {
    for (const issue of issues) {
        markdown += `- **${issue.message}** su **${issue.count}** occorrenze: ${issue.pages.join(', ')}\n`;
    }
}

if (stepSummaryPath) {
    fs.appendFileSync(stepSummaryPath, `${markdown}\n`);
}

process.stdout.write(`${markdown}\n`);
