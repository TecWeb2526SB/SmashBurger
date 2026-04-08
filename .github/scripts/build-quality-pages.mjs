import fs from 'node:fs';
import path from 'node:path';

const argMap = Object.fromEntries(
    process.argv.slice(2).map((arg) => {
        const [key, ...rest] = arg.split('=');
        return [key, rest.join('=')];
    })
);

const sourceDir = path.resolve(process.cwd(), argMap['--source-dir'] || '.tmp/pages-source');
const outputDir = path.resolve(process.cwd(), argMap['--output-dir'] || '.tmp/quality-dashboard');

const contextPath = path.join(sourceDir, 'context.json');
const context = fs.existsSync(contextPath)
    ? JSON.parse(fs.readFileSync(contextPath, 'utf8'))
    : {};

const suites = [
    {
        id: 'lighthouse',
        label: 'Lighthouse',
        summaryPath: path.join(sourceDir, 'lighthouse', 'summary.json'),
    },
    {
        id: 'pa11y',
        label: 'Pa11y',
        summaryPath: path.join(sourceDir, 'pa11y', 'summary.json'),
    },
    {
        id: 'w3c',
        label: 'W3C',
        summaryPath: path.join(sourceDir, 'w3c', 'summary.json'),
    },
    {
        id: 'authenticated',
        label: 'Authenticated',
        summaryPath: path.join(sourceDir, 'authenticated', 'summary.json'),
    },
];

const loadedSuites = suites.map((suite) => ({
    ...suite,
    summary: fs.existsSync(suite.summaryPath)
        ? JSON.parse(fs.readFileSync(suite.summaryPath, 'utf8'))
        : null,
}));

fs.mkdirSync(outputDir, { recursive: true });
fs.mkdirSync(path.join(outputDir, 'data'), { recursive: true });

for (const suite of loadedSuites) {
    if (suite.summary) {
        fs.copyFileSync(suite.summaryPath, path.join(outputDir, 'data', `${suite.id}.json`));
    }
}

if (fs.existsSync(contextPath)) {
    fs.copyFileSync(contextPath, path.join(outputDir, 'data', 'context.json'));
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll('\'', '&#39;');
}

function formatDate(value) {
    if (!value) {
        return 'n/d';
    }

    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return value;
    }

    return new Intl.DateTimeFormat('it-IT', {
        dateStyle: 'medium',
        timeStyle: 'short',
        timeZone: 'Europe/Rome',
    }).format(date);
}

function statusMeta(status) {
    const normalized = status || 'missing';
    const map = {
        pass: { label: 'Pass', className: 'pass' },
        warn: { label: 'Warning', className: 'warn' },
        fail: { label: 'Fail', className: 'fail' },
        missing: { label: 'Pending', className: 'missing' },
    };

    return map[normalized] || map.missing;
}

function renderStatusBadge(status) {
    const meta = statusMeta(status);
    return `<span class="status-badge status-${meta.className}">${escapeHtml(meta.label)}</span>`;
}

function renderSuiteCard(suite) {
    if (!suite.summary) {
        return `
            <article class="metric-card">
                <h3>${escapeHtml(suite.label)}</h3>
                <p class="metric-value">In attesa</p>
                <p class="metric-note">Il summary non e ancora disponibile per questo commit.</p>
            </article>
        `;
    }

    if (suite.id === 'lighthouse') {
        return `
            <article class="metric-card">
                <h3>${escapeHtml(suite.label)}</h3>
                <p class="metric-value">${suite.summary.warningPages}/${suite.summary.totalPages}</p>
                <p class="metric-note">Pagine pubbliche con warning</p>
            </article>
        `;
    }

    if (suite.id === 'pa11y') {
        return `
            <article class="metric-card">
                <h3>${escapeHtml(suite.label)}</h3>
                <p class="metric-value">${suite.summary.totalIssues}</p>
                <p class="metric-note">Issue accessibilita rilevate</p>
            </article>
        `;
    }

    if (suite.id === 'w3c') {
        return `
            <article class="metric-card">
                <h3>${escapeHtml(suite.label)}</h3>
                <p class="metric-value">${suite.summary.totalErrors}</p>
                <p class="metric-note">Errori HTML/CSS rilevati</p>
            </article>
        `;
    }

    const results = Array.isArray(suite.summary.results) ? suite.summary.results : [];
    const failed = results.filter((item) => item.status === 'fail').length;
    const warned = results.filter((item) => item.status === 'warn').length;
    return `
        <article class="metric-card">
            <h3>${escapeHtml(suite.label)}</h3>
            <p class="metric-value">${failed}/${results.length}</p>
            <p class="metric-note">Check autenticati falliti (${warned} warning)</p>
        </article>
    `;
}

function renderIssueList(items, formatter, emptyMessage) {
    if (!Array.isArray(items) || items.length === 0) {
        return `<p class="empty-state">${escapeHtml(emptyMessage)}</p>`;
    }

    return `
        <ul class="issue-list">
            ${items.map((item) => `<li>${formatter(item)}</li>`).join('')}
        </ul>
    `;
}

function renderLighthouseSection(summary) {
    if (!summary) {
        return renderPendingSection('Lighthouse', 'Il workflow Lighthouse non ha ancora prodotto un summary per questo commit.');
    }

    const rows = (summary.pages || []).map((page) => `
        <tr>
            <td><code>${escapeHtml(page.page)}</code></td>
            <td>${page.scores?.performance ?? 'n/d'}</td>
            <td>${page.scores?.accessibility ?? 'n/d'}</td>
            <td>${page.scores?.['best-practices'] ?? 'n/d'}</td>
            <td>${page.scores?.seo ?? 'n/d'}</td>
            <td>${renderStatusBadge(page.status)}</td>
        </tr>
    `).join('');

    const issues = renderIssueList(
        summary.issues,
        (issue) => `
            <strong>${escapeHtml(issue.title)}</strong>
            <span>${issue.count} pagine, score medio ${issue.averageScore}/100</span>
        `,
        'Nessun audit prioritario sotto soglia.'
    );

    return renderSuiteSection({
        title: 'Lighthouse',
        meta: [
            `${summary.scannedPages}/${summary.totalPages} pagine analizzate`,
            `${summary.warningPages} pagine con warning`,
            summary.runUrl ? `<a href="${escapeHtml(summary.runUrl)}">Apri run GitHub</a>` : null,
        ],
        issues,
        tableHead: `
            <tr>
                <th>Pagina</th>
                <th>Perf.</th>
                <th>Acc.</th>
                <th>Best</th>
                <th>SEO</th>
                <th>Stato</th>
            </tr>
        `,
        tableBody: rows,
    });
}

function renderPa11ySection(summary) {
    if (!summary) {
        return renderPendingSection('Pa11y', 'Il workflow Pa11y non ha ancora prodotto un summary per questo commit.');
    }

    const rows = (summary.pages || []).map((page) => `
        <tr>
            <td><code>${escapeHtml(page.page)}</code></td>
            <td>${page.issues >= 0 ? page.issues : 'n/d'}</td>
            <td>${renderStatusBadge(page.status)}</td>
        </tr>
    `).join('');

    const issues = renderIssueList(
        summary.issues,
        (issue) => `
            <strong>${escapeHtml(issue.message)}</strong>
            <span>${issue.count} occorrenze, pagine: ${escapeHtml(issue.pages.join(', '))}</span>
        `,
        'Nessuna issue Pa11y rilevata.'
    );

    return renderSuiteSection({
        title: 'Pa11y',
        meta: [
            `${summary.totalPages - summary.missingPages}/${summary.totalPages} pagine analizzate`,
            `${summary.totalIssues} issue totali`,
            summary.runUrl ? `<a href="${escapeHtml(summary.runUrl)}">Apri run GitHub</a>` : null,
        ],
        issues,
        tableHead: `
            <tr>
                <th>Pagina</th>
                <th>Issue</th>
                <th>Stato</th>
            </tr>
        `,
        tableBody: rows,
    });
}

function renderW3cSection(summary) {
    if (!summary) {
        return renderPendingSection('W3C', 'Il workflow W3C non ha ancora prodotto un summary per questo commit.');
    }

    const rows = (summary.pages || []).map((page) => `
        <tr>
            <td><code>${escapeHtml(page.page)}</code></td>
            <td>${page.errors >= 0 ? page.errors : 'n/d'}</td>
            <td>${renderStatusBadge(page.status)}</td>
        </tr>
    `).join('');

    const issues = renderIssueList(
        summary.issues,
        (issue) => `
            <strong>${escapeHtml(issue.message)}</strong>
            <span>${issue.count} occorrenze, pagine: ${escapeHtml(issue.pages.join(', '))}</span>
        `,
        'Nessun errore W3C rilevato.'
    );

    return renderSuiteSection({
        title: 'W3C',
        meta: [
            `${summary.totalPages - summary.missingPages}/${summary.totalPages} pagine analizzate`,
            `${summary.totalErrors} errori totali`,
            summary.runUrl ? `<a href="${escapeHtml(summary.runUrl)}">Apri run GitHub</a>` : null,
        ],
        issues,
        tableHead: `
            <tr>
                <th>Pagina</th>
                <th>Errori</th>
                <th>Stato</th>
            </tr>
        `,
        tableBody: rows,
    });
}

function renderAuthenticatedSection(summary) {
    if (!summary) {
        return renderPendingSection('Authenticated', 'Il workflow autenticato non ha ancora prodotto un summary per questo commit.');
    }

    const rows = (summary.results || []).map((item) => {
        const lighthouseText = item.lighthouse
            ? `P ${item.lighthouse.performance} / A ${item.lighthouse.accessibility} / BP ${item.lighthouse.bestPractices} / SEO ${item.lighthouse.seo}`
            : 'n/d';

        return `
            <tr>
                <td><code>${escapeHtml(item.role)}</code></td>
                <td><code>${escapeHtml(item.page)}</code></td>
                <td>${escapeHtml(item.auth)}</td>
                <td>${escapeHtml(lighthouseText)}</td>
                <td>${item.pa11yIssues ?? 'n/d'}</td>
                <td>${renderStatusBadge(item.status)}</td>
            </tr>
        `;
    }).join('');

    const issues = renderIssueList(
        (summary.results || []).filter((item) => item.status !== 'pass'),
        (item) => `
            <strong>${escapeHtml(`${item.role} / ${item.page}`)}</strong>
            <span>${escapeHtml((item.focusAreas || []).join(' | ') || 'Verificare il dettaglio del run')}</span>
        `,
        'Nessun problema rilevato sulle pagine interne.'
    );

    return renderSuiteSection({
        title: 'Authenticated Internal Pages',
        meta: [
            `${(summary.results || []).length} check eseguiti`,
            `${(summary.results || []).filter((item) => item.status === 'fail').length} fail`,
            summary.runUrl ? `<a href="${escapeHtml(summary.runUrl)}">Apri run GitHub</a>` : null,
        ],
        issues,
        tableHead: `
            <tr>
                <th>Ruolo</th>
                <th>Pagina</th>
                <th>Auth</th>
                <th>Lighthouse</th>
                <th>Pa11y</th>
                <th>Stato</th>
            </tr>
        `,
        tableBody: rows,
    });
}

function renderPendingSection(title, message) {
    return `
        <section class="suite-panel">
            <div class="suite-header">
                <div>
                    <p class="eyebrow">${escapeHtml(title)}</p>
                    <h2>${escapeHtml(title)}</h2>
                </div>
                ${renderStatusBadge('missing')}
            </div>
            <p class="empty-state">${escapeHtml(message)}</p>
        </section>
    `;
}

function renderSuiteSection({ title, meta, issues, tableHead, tableBody }) {
    return `
        <section class="suite-panel">
            <div class="suite-header">
                <div>
                    <p class="eyebrow">Workflow summary</p>
                    <h2>${escapeHtml(title)}</h2>
                </div>
            </div>
            <div class="suite-meta">
                ${(meta || [])
                    .filter(Boolean)
                    .map((item) => `<span>${item}</span>`)
                    .join('')}
            </div>
            <div class="suite-grid">
                <div class="issues-panel">
                    <h3>Cose da sistemare prima</h3>
                    ${issues}
                </div>
                <div class="table-panel">
                    <div class="table-wrap">
                        <table>
                            <thead>${tableHead}</thead>
                            <tbody>${tableBody}</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    `;
}

const availableSuites = loadedSuites.filter((suite) => suite.summary).length;

const html = `<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quality Dashboard</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0d1117;
            --panel: rgba(22, 27, 34, 0.92);
            --panel-soft: rgba(255, 255, 255, 0.04);
            --border: rgba(255, 255, 255, 0.1);
            --text: #f5f7fb;
            --muted: #98a2b3;
            --accent: #f45a43;
            --accent-soft: rgba(244, 90, 67, 0.14);
            --green: #1f9d63;
            --green-soft: rgba(31, 157, 99, 0.18);
            --amber: #d19a1c;
            --amber-soft: rgba(209, 154, 28, 0.2);
            --red: #d84f68;
            --red-soft: rgba(216, 79, 104, 0.2);
            --slate: #7d8590;
            --slate-soft: rgba(125, 133, 144, 0.18);
            --shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top, rgba(244, 90, 67, 0.18), transparent 32%),
                linear-gradient(180deg, #111826 0%, var(--bg) 100%);
            color: var(--text);
        }

        a { color: #ffd1c8; }

        .page {
            width: min(1240px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0 72px;
        }

        .hero {
            display: grid;
            gap: 20px;
            padding: 32px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow);
        }

        .eyebrow {
            margin: 0 0 10px;
            color: var(--accent);
            font-size: 0.82rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        h1, h2, h3, p { margin: 0; }

        h1 {
            font-size: clamp(2.1rem, 4vw, 3.7rem);
            line-height: 0.98;
        }

        .hero-copy {
            max-width: 840px;
            display: grid;
            gap: 14px;
        }

        .hero-copy p {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.6;
        }

        .hero-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-meta span {
            padding: 10px 14px;
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .metrics {
            margin-top: 24px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
        }

        .metric-card,
        .suite-panel {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow);
        }

        .metric-card {
            padding: 22px;
            display: grid;
            gap: 8px;
        }

        .metric-card h3 {
            font-size: 0.95rem;
            color: var(--muted);
        }

        .metric-value {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
        }

        .metric-note {
            color: var(--muted);
            line-height: 1.5;
        }

        .suite-list {
            margin-top: 24px;
            display: grid;
            gap: 18px;
        }

        .suite-panel {
            padding: 26px;
        }

        .suite-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .suite-header h2 {
            font-size: 1.7rem;
        }

        .suite-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .suite-meta span {
            padding: 8px 12px;
            background: var(--panel-soft);
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--muted);
            font-size: 0.92rem;
        }

        .suite-grid {
            display: grid;
            grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
            gap: 18px;
        }

        .issues-panel,
        .table-panel {
            background: rgba(255, 255, 255, 0.025);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 18px;
        }

        .issues-panel {
            display: grid;
            gap: 14px;
            align-content: start;
        }

        .issues-panel h3 {
            font-size: 1rem;
        }

        .issue-list {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 10px;
            color: var(--muted);
        }

        .issue-list strong {
            display: block;
            margin-bottom: 4px;
            color: var(--text);
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 540px;
        }

        th, td {
            text-align: left;
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        code {
            font-family: "SFMono-Regular", ui-monospace, monospace;
            color: #ffd6ce;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .status-pass {
            background: var(--green-soft);
            border-color: rgba(31, 157, 99, 0.32);
            color: #93e2bb;
        }

        .status-warn {
            background: var(--amber-soft);
            border-color: rgba(209, 154, 28, 0.34);
            color: #ffd88a;
        }

        .status-fail {
            background: var(--red-soft);
            border-color: rgba(216, 79, 104, 0.34);
            color: #ffafbf;
        }

        .status-missing {
            background: var(--slate-soft);
            border-color: rgba(125, 133, 144, 0.34);
            color: #c3cad4;
        }

        .empty-state {
            color: var(--muted);
            line-height: 1.6;
        }

        .footer {
            margin-top: 22px;
            color: var(--muted);
            font-size: 0.92rem;
            text-align: center;
        }

        @media (max-width: 900px) {
            .suite-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .page {
                width: min(100% - 20px, 1240px);
                padding-top: 24px;
                padding-bottom: 48px;
            }

            .hero,
            .suite-panel {
                padding: 20px;
                border-radius: 20px;
            }

            .metric-value {
                font-size: 1.9rem;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="hero">
            <div class="hero-copy">
                <p class="eyebrow">GitHub Pages</p>
                <h1>Quality Dashboard</h1>
                <p>Risultati pubblicati automaticamente dai workflow GitHub Actions, senza aggiornare il README e senza sporcare la history del repository.</p>
            </div>
            <div class="hero-meta">
                <span>Repository: ${escapeHtml(context.repository || 'n/d')}</span>
                <span>Branch: ${escapeHtml(context.branch || 'n/d')}</span>
                <span>Commit: ${escapeHtml((context.sha || '').slice(0, 12) || 'n/d')}</span>
                <span>Aggiornato: ${escapeHtml(formatDate(context.generatedAt))}</span>
                <span>Summary disponibili: ${availableSuites}/${loadedSuites.length}</span>
            </div>
        </section>

        <section class="metrics">
            ${loadedSuites.map(renderSuiteCard).join('')}
        </section>

        <section class="suite-list">
            ${renderLighthouseSection(loadedSuites.find((suite) => suite.id === 'lighthouse')?.summary)}
            ${renderPa11ySection(loadedSuites.find((suite) => suite.id === 'pa11y')?.summary)}
            ${renderW3cSection(loadedSuites.find((suite) => suite.id === 'w3c')?.summary)}
            ${renderAuthenticatedSection(loadedSuites.find((suite) => suite.id === 'authenticated')?.summary)}
        </section>

        <p class="footer">I file JSON usati per generare questa pagina sono disponibili nella cartella <code>data/</code> della build Pages.</p>
    </main>
</body>
</html>
`;

fs.writeFileSync(path.join(outputDir, 'index.html'), html);
