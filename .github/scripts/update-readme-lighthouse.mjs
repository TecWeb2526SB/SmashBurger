import fs from 'node:fs';
import path from 'node:path';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const readmePath = path.resolve(process.cwd(), argMap['--readme'] || 'README.md');
const summaryPath = path.resolve(process.cwd(), argMap['--summary'] || '.tmp/lighthouse/summary.json');
const startMarker = '<!-- ci:lighthouse:start -->';
const endMarker = '<!-- ci:lighthouse:end -->';

const readme = fs.readFileSync(readmePath, 'utf8');
const summary = JSON.parse(fs.readFileSync(summaryPath, 'utf8'));

const iconForStatus = {
    pass: 'OK',
    warn: 'ATTN',
    missing: 'ERR',
};

let section = '## Quality Snapshot\n';
section += 'Ultimo aggiornamento automatico dai workflow GitHub Actions.\n\n';
section += `- Ultimo aggiornamento: \`${summary.generatedAt}\`\n`;
section += `- Pagine pubbliche coperte da Lighthouse: **${summary.scannedPages}/${summary.totalPages}**\n`;
section += `- Pagine con warning: **${summary.warningPages}**\n`;
if (summary.runUrl) {
    section += `- Run GitHub: [apri il report completo](${summary.runUrl})\n`;
}
section += '\n### Lighthouse\n\n';
section += '| Pagina | Performance | Accessibility | Best practices | SEO | Stato |\n';
section += '| --- | ---: | ---: | ---: | ---: | --- |\n';

for (const page of summary.pages) {
    section += `| \`${page.page}\` | ${page.scores.performance ?? 'n/d'} | ${page.scores.accessibility ?? 'n/d'} | ${page.scores['best-practices'] ?? 'n/d'} | ${page.scores.seo ?? 'n/d'} | ${iconForStatus[page.status] || 'n/d'} |\n`;
}

section += '\n### Priorita da sistemare\n\n';
if (!Array.isArray(summary.issues) || summary.issues.length === 0) {
    section += '- Nessun audit prioritario sotto soglia nelle pagine analizzate.\n';
} else {
    for (const issue of summary.issues.slice(0, 6)) {
        section += `- **${issue.title}** su **${issue.count}** pagine (score medio ${issue.averageScore}/100)\n`;
    }
}

const replacement = `${startMarker}\n${section}\n${endMarker}`;
const markerRegex = new RegExp(`${startMarker}[\\s\\S]*?${endMarker}`, 'm');
const nextReadme = markerRegex.test(readme)
    ? readme.replace(markerRegex, replacement)
    : `${readme.trimEnd()}\n\n${replacement}\n`;

fs.writeFileSync(readmePath, nextReadme);
