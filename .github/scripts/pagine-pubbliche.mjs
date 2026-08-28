/**
 * Stampa i percorsi delle pagine pubbliche elencate in src/sitemap.xml, uno per riga.
 *
 * I controlli di qualità usano questo elenco come fonte unica: aggiungere una pagina
 * alla sitemap la mette automaticamente sotto controllo.
 */

import fs from 'node:fs';

const percorsoSitemap = process.argv[2] ?? 'src/sitemap.xml';
const xml = fs.readFileSync(percorsoSitemap, 'utf8');
const pagine = [];

for (const trovato of xml.matchAll(/<loc>([^<]+)<\/loc>/g)) {
    const percorso = new URL(trovato[1]).pathname.replace(/\/+$/, '') || '/';

    if (!pagine.includes(percorso)) {
        pagine.push(percorso);
    }
}

if (pagine.length === 0) {
    throw new Error(`Nessuna pagina pubblica trovata in ${percorsoSitemap}`);
}

process.stdout.write(`${pagine.join('\n')}\n`);
