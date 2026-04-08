import fs from 'node:fs';
import path from 'node:path';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const reportsDir = path.resolve(process.cwd(), argMap['--reports-dir'] || '.tmp/w3c/reports');
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
    const reportPath = path.join(reportsDir, `${slug}.txt`);

    if (!fs.existsSync(reportPath)) {
        pageResults.push({ page, errors: -1, status: 'missing' });
        continue;
    }

    const raw = fs.readFileSync(reportPath, 'utf8').trim();
    const lines = raw === '' ? [] : raw.split(/\r?\n/).filter(Boolean);
    const errorLines = lines.filter((line) => line.includes(': error:'));

    for (const line of errorLines.slice(0, 20)) {
        const match = line.match(/: error: (.+)$/);
        const message = match ? match[1] : line;
        const current = messageMap.get(message) || {
            message,
            count: 0,
            pages: new Set(),
        };
        current.count += 1;
        current.pages.add(page);
        messageMap.set(message, current);
    }

    pageResults.push({
        page,
        errors: errorLines.length,
        status: errorLines.length === 0 ? 'pass' : 'warn',
    });
}

const issues = [...messageMap.values()]
    .map((issue) => ({ ...issue, pages: [...issue.pages] }))
    .sort((a, b) => b.count - a.count)
    .slice(0, 8);

const missingPages = pageResults.filter((page) => page.status === 'missing').length;
const invalidPages = pageResults.filter((page) => page.status === 'warn').length;
const totalErrors = pageResults.reduce((sum, page) => sum + Math.max(page.errors, 0), 0);
const runUrl = process.env.GITHUB_RUN_ID
    ? `${process.env.GITHUB_SERVER_URL}/${process.env.GITHUB_REPOSITORY}/actions/runs/${process.env.GITHUB_RUN_ID}`
    : null;

const summary = {
    generatedAt: new Date().toISOString(),
    runUrl,
    totalPages: pageResults.length,
    missingPages,
    invalidPages,
    totalErrors,
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

let markdown = '## W3C overview\n\n';
markdown += `- Pagine analizzate: **${pageResults.length - missingPages}/${pageResults.length}**\n`;
markdown += `- Pagine con errori: **${invalidPages}**\n`;
markdown += `- Errori HTML/CSS rilevati: **${totalErrors}**\n`;
if (runUrl) {
    markdown += `- Run GitHub: [apri il dettaglio](${runUrl})\n`;
}
markdown += '\n';
markdown += '| Pagina | Errori | Stato |\n';
markdown += '| --- | ---: | --- |\n';

for (const result of pageResults) {
    markdown += `| \`${result.page}\` | ${result.errors >= 0 ? result.errors : 'n/d'} | ${statusIcon[result.status]} |\n`;
}

markdown += '\n### Cose da sistemare prima\n\n';
if (issues.length === 0) {
    markdown += '- Nessun errore W3C rilevato nelle pagine analizzate.\n';
} else {
    for (const issue of issues) {
        markdown += `- **${issue.message}** su **${issue.count}** occorrenze: ${issue.pages.join(', ')}\n`;
    }
}

if (stepSummaryPath) {
    fs.appendFileSync(stepSummaryPath, `${markdown}\n`);
}

process.stdout.write(`${markdown}\n`);
