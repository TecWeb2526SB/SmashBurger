#!/usr/bin/env bash
# Controlli da eseguire prima di ogni commit, secondo REGOLE.md sezione 25.
#
# Esce con codice diverso da zero al primo controllo fallito, cosi' un uso in catena non
# puo' proseguire su un esito negativo.

set -u

radice="$(cd "$(dirname "$0")" && pwd)"
cd "$radice" || exit 1

base="${BASE_URL:-http://127.0.0.1:8080}"
esito=0

segnala() {
    echo "FALLITO: $1"
    esito=1
}

echo "== caratteri ammessi =="
python - <<'PY' || segnala "caratteri vietati nel codice"
import pathlib, sys
vietati = {0x2010, 0x2011, 0x2012, 0x2013, 0x2014, 0x2015, 0x2018, 0x2019,
           0x201C, 0x201D, 0x2026, 0x2022, 0x2192, 0x00A0, 0x2212}
trovati = 0
for f in list(pathlib.Path('src').rglob('*.php')) + list(pathlib.Path('src').rglob('*.sql')):
    for n, riga in enumerate(f.read_text(encoding='utf-8').splitlines(), 1):
        for c in riga:
            if ord(c) in vietati or 0x1F300 <= ord(c) <= 0x1FAFF:
                print(f'  {f}:{n}: {hex(ord(c))}')
                trovati += 1
print('  nessun carattere vietato' if not trovati else f'  {trovati} caratteri vietati')
sys.exit(1 if trovati else 0)
PY

# I limiti qui sotto sono gli stessi della tabella in REGOLE.md sezione 22: cambiandone
# uno va cambiato anche l'altro, altrimenti il documento e il controllo dicono cose
# diverse.
echo "== budget di REGOLE.md sezione 22 =="
python - <<'PY' || segnala "budget superati"
import pathlib, re, sys

def righe(percorsi):
    return sum(len(p.read_text(encoding='utf-8').splitlines()) for p in percorsi)

sforati = []

css = list(pathlib.Path('src/styles').glob('*.css'))
js = list(pathlib.Path('src/scripts').glob('*.js'))

controlli = [
    ('file CSS', len(css), 3),
    ('righe CSS', righe(css), 2400),
    ('file JavaScript', len(js), 1),
    ('righe JavaScript', righe(js), 800),
]

sql = pathlib.Path('src/database/schema.sql')
if sql.is_file():
    controlli.append(('tabelle', len(re.findall(r'^CREATE TABLE', sql.read_text(encoding='utf-8'), re.M)), 12))

# Gli id generati per riga contano una volta sola: il loro numero cresce con i dati,
# non con il codice, quindi il suffisso numerico viene tolto prima di contarli.
classi, identificativi = set(), set()
for f in pathlib.Path('src').rglob('*.php'):
    testo = f.read_text(encoding='utf-8')
    for valore in re.findall(r'class="([^"<>]*)"', testo):
        classi.update(v for v in valore.split() if v and '<' not in v)
    for valore in re.findall(r'\bid="([^"<>]*)"', testo):
        identificativi.add(re.sub(r'-?\d+$', '', valore.split('<')[0]).strip('-'))

controlli.append(('classi CSS distinte', len(classi), 110))
controlli.append(('id distinti', len(identificativi - {''}), 90))

for nome, valore, limite in controlli:
    stato = 'ok' if valore <= limite else 'OLTRE IL LIMITE'
    print(f'  {nome}: {valore} / {limite} {stato}')
    if valore > limite:
        sforati.append(nome)

for f in pathlib.Path('src').rglob('*.php'):
    n = len(f.read_text(encoding='utf-8').splitlines())
    limite = 150 if 'views' in str(f) else 300
    if n > limite:
        print(f'  {f}: {n} righe, limite {limite}')
        sforati.append(str(f))

sys.exit(1 if sforati else 0)
PY

echo "== markup delle pagine servite =="
if curl -fsS -o /dev/null "$base/" 2>/dev/null; then
    cartella="$(mktemp -d)"
    # La pagina di errore va scaricata insieme alle altre, quindi non si usa curl -f:
    # il suo 404 e' il comportamento atteso, non un guasto. Si controlla che ogni
    # risposta abbia un corpo e uno stato fra quelli previsti.
    while IFS= read -r pagina; do
        nome="$(printf '%s' "${pagina:-home}" | tr '/?=&' '----')"
        stato="$(curl -sS -o "$cartella/$nome.html" -w '%{http_code}' "$base/$pagina")"
        atteso=200
        [ "$pagina" = 'non-esiste' ] && atteso=404
        [ "$stato" = "$atteso" ] || segnala "$pagina ha risposto $stato invece di $atteso"
        [ -s "$cartella/$nome.html" ] || segnala "$pagina ha restituito una pagina vuota"
    done <<'ELENCO'

menu
prodotto?slug=cheeseburger
sedi
sede?slug=padova
servizi
chi-siamo
contatti
privacy
accessibilita
mappa-sito
accedi
registrati
non-esiste
ELENCO

    python - "$cartella" <<'PY' || segnala "markup non conforme alla sintassi XML"
import pathlib, sys, xml.etree.ElementTree as ET
errori = 0
for f in sorted(pathlib.Path(sys.argv[1]).glob('*.html')):
    testo = f.read_text(encoding='utf-8').replace('<!DOCTYPE html>', '', 1)
    try:
        ET.fromstring(testo)
    except ET.ParseError as err:
        print(f'  {f.name}: {err}')
        errori += 1
print(f'  {errori} pagine non ben formate' if errori else '  tutte le pagine sono XML ben formato')
sys.exit(1 if errori else 0)
PY
    rm -rf "$cartella"
else
    echo "  sito non raggiungibile su $base, controllo saltato"
fi

echo
if [ "$esito" -eq 0 ]; then
    echo "tutti i controlli superati"
else
    echo "ci sono controlli falliti"
fi

exit "$esito"
