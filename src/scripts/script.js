(function () {
    'use strict';

    var tornaSu = document.querySelector('.torna-su');
    var areaScorribile = document.querySelector('.pagina-scorribile');
    var footer = document.querySelector('.pagina-scorribile > footer');
    var header = document.querySelector('body > header');

    if (tornaSu === null) {
        return;
    }

    function aggiornaTornaSu() {
        var scorrimento = areaScorribile === null
            ? (window.scrollY || document.documentElement.scrollTop)
            : areaScorribile.scrollTop;
        var altezzaVisibile = areaScorribile === null ? window.innerHeight : areaScorribile.clientHeight;
        var soglia = Math.max(400, altezzaVisibile * 0.65);
        var rialzoFooter = 0;
        var mostra = scorrimento > soglia;

        if (footer !== null) {
            var cimaFooter = footer.getBoundingClientRect().top;
            var fondoHeader = header === null ? 0 : header.getBoundingClientRect().bottom;

            rialzoFooter = Math.max(0, window.innerHeight - cimaFooter);

            if (rialzoFooter > 0 && cimaFooter - fondoHeader < tornaSu.offsetHeight + 32) {
                mostra = false;
            }
        }

        tornaSu.setAttribute('data-visibile', mostra ? 'true' : 'false');
        tornaSu.style.setProperty('--rialzo-footer', rialzoFooter + 'px');
    }

    function aggiornaAltezzaHeader() {
        if (header !== null) {
            document.documentElement.style.setProperty('--altezza-header', header.offsetHeight + 'px');
        }
    }

    aggiornaAltezzaHeader();
    aggiornaTornaSu();
    (areaScorribile || window).addEventListener('scroll', aggiornaTornaSu, { passive: true });
    window.addEventListener('resize', function () {
        aggiornaAltezzaHeader();
        aggiornaTornaSu();
    });

    if (header !== null && 'ResizeObserver' in window) {
        new ResizeObserver(aggiornaAltezzaHeader).observe(header);
    }
}());
