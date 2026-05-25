<?php
/**
 * Kompresi PDF di sisi browser (untuk shared hosting tanpa Ghostscript).
 * Saat submit form, PDF besar pada input file (accept application/pdf)
 * dirasterisasi via PDF.js + dirakit ulang via pdf-lib sebelum dikirim.
 * Library di-load lazy (hanya ketika benar-benar mengompres).
 */
?>
<script>
(function () {
    var THRESHOLD = 1.5 * 1024 * 1024; // hanya kompres file > 1.5 MB
    var MAX_PAGES = 20;                // lewati dokumen sangat panjang (hindari hang)
    var DPI = 144, QUALITY = 0.65;
    var PDFJS = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js';
    var PDFJS_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
    var PDFLIB = 'https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js';

    function loadScript(src) {
        return new Promise(function (res, rej) {
            var s = document.createElement('script');
            s.src = src; s.onload = res; s.onerror = rej; document.head.appendChild(s);
        });
    }
    async function ensureLibs() {
        if (!window.pdfjsLib) await loadScript(PDFJS);
        if (!window.PDFLib) await loadScript(PDFLIB);
        if (window.pdfjsLib) pdfjsLib.GlobalWorkerOptions.workerSrc = PDFJS_WORKER;
    }

    async function compress(file) {
        await ensureLibs();
        if (!window.pdfjsLib || !window.PDFLib) return null;
        var buf = await file.arrayBuffer();
        var src = await pdfjsLib.getDocument({ data: buf }).promise;
        if (src.numPages > MAX_PAGES) return null;
        var out = await PDFLib.PDFDocument.create();
        var scale = DPI / 72;
        for (var p = 1; p <= src.numPages; p++) {
            var page = await src.getPage(p);
            var vp = page.getViewport({ scale: scale });
            var canvas = document.createElement('canvas');
            canvas.width = Math.ceil(vp.width); canvas.height = Math.ceil(vp.height);
            var ctx = canvas.getContext('2d');
            ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height);
            await page.render({ canvasContext: ctx, viewport: vp }).promise;
            var dataUrl = canvas.toDataURL('image/jpeg', QUALITY);
            var jpg = await out.embedJpg(dataUrl);
            var wPt = vp.width * 72 / DPI, hPt = vp.height * 72 / DPI;
            var pg = out.addPage([wPt, hPt]);
            pg.drawImage(jpg, { x: 0, y: 0, width: wPt, height: hPt });
            canvas.width = canvas.height = 0; // bebaskan memori
        }
        var bytes = await out.save();
        return new Blob([bytes], { type: 'application/pdf' });
    }

    function setFile(input, blob, name) {
        try {
            var dt = new DataTransfer();
            dt.items.add(new File([blob], name, { type: 'application/pdf' }));
            input.files = dt.files;
            return true;
        } catch (e) { return false; }
    }

    function pdfInputs(form) {
        return Array.prototype.filter.call(form.querySelectorAll('input[type=file]'), function (i) {
            return (i.accept || '').indexOf('pdf') !== -1 && i.files && i.files[0] &&
                i.files[0].type === 'application/pdf' && i.files[0].size > THRESHOLD;
        });
    }

    document.addEventListener('submit', function (ev) {
        var form = ev.target;
        if (!(form instanceof HTMLFormElement) || form.dataset.pdfCompressed === '1') return;
        var targets = pdfInputs(form);
        if (targets.length === 0) return;

        ev.preventDefault();
        var btn = form.querySelector('[type=submit]');
        var label = btn ? btn.innerHTML : '';
        if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengompres PDF…'; }

        Promise.all(targets.map(function (input) {
            var orig = input.files[0];
            return compress(orig).then(function (blob) {
                if (blob && blob.size > 0 && blob.size < orig.size) {
                    setFile(input, blob, (orig.name || 'dokumen').replace(/\.pdf$/i, '') + '.pdf');
                }
            }).catch(function () { /* biarkan file asli */ });
        })).then(function () {
            form.dataset.pdfCompressed = '1';
            if (btn) { btn.disabled = false; btn.innerHTML = label; }
            if (typeof form.requestSubmit === 'function') form.requestSubmit(); else form.submit();
        });
    }, true);
})();
</script>
