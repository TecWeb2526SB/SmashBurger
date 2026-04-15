import fs from 'node:fs';
import path from 'node:path';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const reportsDir = path.resolve(process.cwd(), argMap['--reports-dir'] || '.tmp/lighthouse');
const pagesFile = path.resolve(process.cwd(), argMap['--pages-file'] || '.tmp/public-pages.txt');
const outputJsonPath = path.resolve(process.cwd(), argMap['--output-json'] || path.join(reportsDir, 'summary.json'));
const stepSummaryPath = argMap['--step-summary'] || '';

const pages = fs.readFileSync(pagesFile, 'utf8')
    .split(/\r?\n/)
    .map((line) => line.trim())
    .filter(Boolean);

const categoryOrder = ['performance', 'accessibility', 'best-practices', 'seo'];
const categoryLabels = {
    performance: 'Performance',
    accessibility: 'Accessibility',
    'best-practices': 'Best practices',
    seo: 'SEO',
};
const deEmphasizedAuditIds = new Set([
    'network-dependency-tree-insight',
    'render-blocking-requests',
    'network-server-latency',
    'diagnostics',
    'forced-reflow-insight',
]);
const deEmphasizedAuditTitles = new Set([
    'Network dependency tree',
    'Render blocking requests',
    'Forced reflow',
]);

function pageToReportSlug(page) {
    const normalizedPage = String(page).trim();
    if (normalizedPage === '' || normalizedPage === '/') {
        return 'home';
    }

    return normalizedPage
        .replace(/^\/+|\/+$/g, '')
        .replace(/\//g, '__');
}

const issueMap = new Map();
const pageResults = [];

for (const page of pages) {
    const slug = pageToReportSlug(page);
    const reportPath = path.join(reportsDir, `${slug}.json`);

    if (!fs.existsSync(reportPath)) {
        pageResults.push({
            page,
            status: 'missing',
            scores: {},
            focusAreas: ['Report mancante'],
        });
        continue;
    }

    const report = JSON.parse(fs.readFileSync(reportPath, 'utf8'));
    const scores = {};
    const focusAreas = [];

    for (const category of categoryOrder) {
        const categoryData = report.categories?.[category];
        const score = categoryData?.score;
        scores[category] = score === null || typeof score !== 'number'
            ? null
            : Math.round(score * 100);
    }

    const rankedAudits = Object.values(report.audits || {})
        .filter((audit) => {
            if (!audit || typeof audit !== 'object') {
                return false;
            }

            const mode = audit.scoreDisplayMode;
            if (mode === 'notApplicable' || mode === 'manual' || mode === 'informative') {
                return false;
            }

            return audit.score !== null && typeof audit.score === 'number' && audit.score < 0.9;
        })
        .sort((a, b) => {
            const aPriorityPenalty = deEmphasizedAuditIds.has(a.id) || deEmphasizedAuditTitles.has(a.title) ? 1 : 0;
            const bPriorityPenalty = deEmphasizedAuditIds.has(b.id) || deEmphasizedAuditTitles.has(b.title) ? 1 : 0;

            if (aPriorityPenalty !== bPriorityPenalty) {
                return aPriorityPenalty - bPriorityPenalty;
            }

            return (a.score ?? 1) - (b.score ?? 1);
        });

    const failingAudits = rankedAudits.slice(0, 5);

    for (const audit of failingAudits) {
        focusAreas.push(audit.title);
        const key = audit.id || audit.title;
        const current = issueMap.get(key) || {
            id: audit.id || key,
            title: audit.title || key,
            count: 0,
            pages: new Set(),
            averageScore: 0,
        };

        current.count += 1;
        current.pages.add(page);
        current.averageScore += typeof audit.score === 'number' ? audit.score : 0;
        issueMap.set(key, current);
    }

    const lowScores = categoryOrder.filter((category) => (scores[category] ?? 100) < 90);
    const status = lowScores.length === 0 ? 'pass' : 'warn';

    pageResults.push({
        page,
        status,
        scores,
        focusAreas: focusAreas.length > 0 ? focusAreas : ['Nessun audit prioritario sotto 90'],
    });
}

const issues = [...issueMap.values()]
    .map((issue) => ({
        ...issue,
        pages: [...issue.pages],
        averageScore: Math.round((issue.averageScore / Math.max(issue.count, 1)) * 100),
    }))
    .sort((a, b) => {
        if (b.count !== a.count) {
            return b.count - a.count;
        }

        return a.averageScore - b.averageScore;
    })
    .slice(0, 8);

const scannedPages = pageResults.filter((page) => page.status !== 'missing').length;
const warningPages = pageResults.filter((page) => page.status === 'warn').length;
const missingPages = pageResults.filter((page) => page.status === 'missing').length;
const runUrl = process.env.GITHUB_RUN_ID
    ? `${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}/actions/runs/${process.env.GITHUB_RUN_ID}`
    : null;

const summary = {
    generatedAt: new Date().toISOString(),
    runUrl,
    scannedPages,
    totalPages: pageResults.length,
    warningPages,
    missingPages,
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

const scoreText = (score) => (typeof score === 'number' ? `${score}` : 'n/d');
let markdown = '## Lighthouse overview\n\n';
markdown += `- Pagine analizzate: **${scannedPages}/${pageResults.length}**\n`;
markdown += `- Pagine con warning: **${warningPages}**\n`;
if (missingPages > 0) {
    markdown += `- Report mancanti: **${missingPages}**\n`;
}
if (runUrl) {
    markdown += `- Run GitHub: [apri il dettaglio](${runUrl})\n`;
}
markdown += '\n';
markdown += '| Pagina | Performance | Accessibility | Best practices | SEO | Stato |\n';
markdown += '| --- | ---: | ---: | ---: | ---: | --- |\n';

for (const result of pageResults) {
    markdown += `| \`${result.page}\` | ${scoreText(result.scores.performance)} | ${scoreText(result.scores.accessibility)} | ${scoreText(result.scores['best-practices'])} | ${scoreText(result.scores.seo)} | ${statusIcon[result.status]} |\n`;
}

markdown += '\n### Cose da sistemare prima\n\n';
if (issues.length === 0) {
    markdown += '- Nessun audit prioritario sotto soglia nelle pagine analizzate.\n';
} else {
    for (const issue of issues) {
        markdown += `- **${issue.title}** su **${issue.count}** pagine (score medio ${issue.averageScore}/100): ${issue.pages.join(', ')}\n`;
    }
}

if (stepSummaryPath) {
    fs.appendFileSync(stepSummaryPath, `${markdown}\n`);
}

process.stdout.write(`${markdown}\n`);
