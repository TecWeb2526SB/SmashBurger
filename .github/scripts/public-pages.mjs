import fs from 'node:fs';
import path from 'node:path';

const args = process.argv.slice(2);
const formatArg = args.find((arg) => arg.startsWith('--format='));
const sitemapArg = args.find((arg) => arg.startsWith('--sitemap='));
const format = formatArg ? formatArg.split('=')[1] : 'text';
const sitemapPath = sitemapArg
    ? sitemapArg.split('=')[1]
    : path.resolve(process.cwd(), 'src', 'sitemap.xml');

const xml = fs.readFileSync(sitemapPath, 'utf8');
const matches = [...xml.matchAll(/<loc>([^<]+)<\/loc>/g)];
const pages = [];

for (const match of matches) {
    try {
        const url = new URL(match[1]);
        const page = url.pathname.replace(/\/+$/, '') || '/';
        if (!pages.includes(page)) {
            pages.push(page);
        }
    } catch (error) {
        // Skip malformed entries and let the caller inspect the sitemap if needed.
    }
}

if (pages.length === 0) {
    throw new Error(`No public pages found in sitemap: ${sitemapPath}`);
}

if (format === 'json') {
    process.stdout.write(`${JSON.stringify(pages, null, 2)}\n`);
} else {
    process.stdout.write(`${pages.join('\n')}\n`);
}
