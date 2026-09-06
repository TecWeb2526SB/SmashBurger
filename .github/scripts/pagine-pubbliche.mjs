/**
 * Stampa i percorsi delle pagine pubbliche elencate nella sitemap, uno per riga.
 *
 * La sitemap non e' un file nel repository: la costruisce src/sitemap.php dall'elenco
 * unico delle pagine, quindi va letta dal sito in esecuzione. I controlli di qualita'
 * usano questo elenco come fonte unica: aggiungere una pagina pubblica con una
 * descrizione la mette automaticamente sotto controllo, senza toccare la pipeline.
 *
 * Accetta come argomento un indirizzo oppure un percorso di file, utile per provare la
 * pipeline su una sitemap salvata.
 */

import fs from 'node:fs';

const base = process.env.BASE_URL ?? 'http://127.0.0.1:8080';
const sorgente = process.argv[2] ?? `${base}/sitemap.xml`;
let xml;

if (/^https?:\/\//.test(sorgente)) {
    const risposta = await fetch(sorgente);

    if (!risposta.ok) {
        throw new Error(`La sitemap ha risposto ${risposta.status}: ${sorgente}`);
    }

    xml = await risposta.text();
} else {
    xml = fs.readFileSync(sorgente, 'utf8');
}

const pagine = [];

for (const trovato of xml.matchAll(/<loc>([^<]+)<\/loc>/g)) {
    const percorso = new URL(trovato[1]).pathname.replace(/\/+$/, '') || '/';

    if (!pagine.includes(percorso)) {
        pagine.push(percorso);
    }
}

if (pagine.length === 0) {
    throw new Error(`Nessuna pagina pubblica trovata in ${sorgente}`);
}

process.stdout.write(`${pagine.join('\n')}\n`);
