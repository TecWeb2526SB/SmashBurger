/**
 * Legge i report di Lighthouse e scrive una tabella con i punteggi delle quattro
 * categorie, espressi da 0 a 100.
 *
 * Esce con codice 1 se una pagina resta sotto la soglia minima indicata in SOGLIA.
 */

import fs from 'node:fs';

const cartella = '.tmp/prestazioni';
const SOGLIA = Number(process.env.SOGLIA ?? 90);
const categorie = ['performance', 'accessibility', 'best-practices', 'seo'];

const righe = [];
let sottoSoglia = 0;

for (const file of fs.readdirSync(cartella).filter((nome) => nome.endsWith('.json'))) {
    const report = JSON.parse(fs.readFileSync(`${cartella}/${file}`, 'utf8'));
    const punteggi = categorie.map((categoria) => Math.round((report.categories[categoria]?.score ?? 0) * 100));

    if (punteggi.some((punteggio) => punteggio < SOGLIA)) {
        sottoSoglia++;
    }

    righe.push(`| ${file.replace('.json', '')} | ${punteggi.join(' | ')} |`);
}

const sommario = [
    `## Prestazioni e buone pratiche, soglia ${SOGLIA}`,
    '',
    '| Pagina | Prestazioni | Accessibilità | Buone pratiche | SEO |',
    '| --- | --- | --- | --- | --- |',
    ...righe.sort(),
    '',
    sottoSoglia === 0
        ? 'Tutte le pagine raggiungono la soglia.'
        : `Pagine sotto la soglia: ${sottoSoglia}.`,
    '',
].join('\n');

if (process.env.GITHUB_STEP_SUMMARY) {
    fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY, sommario);
}

console.log(sommario);
process.exit(sottoSoglia === 0 ? 0 : 1);
