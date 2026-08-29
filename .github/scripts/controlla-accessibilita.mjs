/**
 * Controlla l'accessibilità delle pagine con Pa11y, secondo lo standard WCAG 2.1 AA.
 *
 * Le pagine pubbliche arrivano dalla sitemap, quelle riservate da pagine-riservate.json:
 * per queste lo script esegue prima l'accesso e passa a Pa11y il cookie di sessione, così
 * il controllo vede le pagine come le vede la persona che ha fatto l'accesso.
 *
 * Esce con codice 1 se resta anche un solo errore.
 */

import fs from 'node:fs';
import { execFileSync } from 'node:child_process';
import pa11y from 'pa11y';

const base = process.env.BASE_URL ?? 'http://127.0.0.1:8080';
const cartellaReport = '.tmp/accessibilita';
const configurazioneChrome = {
    chromeLaunchConfig: {
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-dev-shm-usage'],
    },
    standard: 'WCAG2AA',
};

/**
 * Esegue l'accesso e restituisce il cookie di sessione da usare nelle richieste.
 */
async function accedi(utente, password) {
    const pagina = await fetch(`${base}/accedi`);
    const cookieIniziale = pagina.headers.getSetCookie().join('; ');
    const markup = await pagina.text();
    const token = markup.match(/name="token_csrf" value="([a-f0-9]+)"/)?.[1];

    if (!token) {
        throw new Error('Token CSRF non trovato nella pagina di accesso.');
    }

    const risposta = await fetch(`${base}/accedi`, {
        method: 'POST',
        redirect: 'manual',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            Cookie: cookieIniziale,
        },
        body: new URLSearchParams({ token_csrf: token, nome_utente: utente, password }),
    });

    if (risposta.status !== 302) {
        throw new Error(`Accesso non riuscito per ${utente}: stato ${risposta.status}`);
    }

    const cookieFinale = risposta.headers.getSetCookie().join('; ');

    return cookieFinale || cookieIniziale;
}

/**
 * Mette un prodotto nel carrello dell'utente collegato.
 *
 * Senza questo passaggio le pagine del carrello e della conferma d'ordine verrebbero
 * controllate vuote, cioè nello stato meno interessante.
 */
async function preparaCarrello(cookie) {
    const pagina = await fetch(`${base}/menu`, { headers: { Cookie: cookie } });
    const markup = await pagina.text();
    const token = markup.match(/name="token_csrf" value="([a-f0-9]+)"/)?.[1];
    const prodotto = markup.match(/name="prodotto_id" value="(\d+)"/)?.[1];

    if (!token || !prodotto) {
        throw new Error('Non è stato possibile preparare il carrello.');
    }

    await fetch(`${base}/carrello`, {
        method: 'POST',
        redirect: 'manual',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', Cookie: cookie },
        body: new URLSearchParams({
            token_csrf: token,
            azione: 'aggiungi',
            ritorno: 'carrello',
            prodotto_id: prodotto,
            quantita: '2',
        }),
    });
}

/**
 * Esegue Pa11y su una pagina e salva il report. Restituisce il numero di errori.
 */
async function controlla(percorso, cookie, etichetta) {
    const nome = (percorso.replace(/^\//, '') || 'home').replace(/[/?=&]/g, '-');
    const opzioni = { ...configurazioneChrome };

    if (cookie) {
        opzioni.headers = { Cookie: cookie };
    }

    const esito = await pa11y(`${base}${percorso}`, opzioni);
    const errori = esito.issues.filter((problema) => problema.type === 'error');

    fs.writeFileSync(`${cartellaReport}/${etichetta}-${nome}.json`, JSON.stringify(esito.issues, null, 2));

    for (const problema of errori.slice(0, 5)) {
        console.log(`  ${problema.message}`);
        console.log(`    ${problema.selector}`);
    }

    return errori.length;
}

fs.mkdirSync(cartellaReport, { recursive: true });

const pubbliche = execFileSync('node', ['.github/scripts/pagine-pubbliche.mjs'], { encoding: 'utf8' })
    .trim()
    .split('\n');
const riservate = JSON.parse(fs.readFileSync('.github/scripts/pagine-riservate.json', 'utf8'));

const righe = [];
let erroriTotali = 0;

for (const percorso of pubbliche) {
    const errori = await controlla(percorso, null, 'pubblica');
    console.log(`pubblica ${percorso}: ${errori} errori`);
    righe.push({ pagina: percorso, accesso: 'pubblica', errori });
    erroriTotali += errori;
}

for (const [ruolo, dati] of Object.entries(riservate)) {
    const cookie = await accedi(dati.utente, dati.password);

    if (dati.pagine.includes('carrello') || dati.pagine.includes('pagamento')) {
        await preparaCarrello(cookie);
    }

    for (const pagina of dati.pagine) {
        const errori = await controlla(`/${pagina}`, cookie, ruolo);
        console.log(`${ruolo} /${pagina}: ${errori} errori`);
        righe.push({ pagina: `/${pagina}`, accesso: ruolo, errori });
        erroriTotali += errori;
    }
}

const sommario = [
    '## Accessibilità, WCAG 2.1 AA',
    '',
    '| Pagina | Accesso | Errori |',
    '| --- | --- | --- |',
    ...righe.map((riga) => `| ${riga.pagina} | ${riga.accesso} | ${riga.errori} |`),
    '',
    `Totale errori: ${erroriTotali}`,
    '',
].join('\n');

fs.writeFileSync(`${cartellaReport}/riepilogo.md`, sommario);

if (process.env.GITHUB_STEP_SUMMARY) {
    fs.appendFileSync(process.env.GITHUB_STEP_SUMMARY, sommario);
}

console.log(sommario);
process.exit(erroriTotali === 0 ? 0 : 1);
