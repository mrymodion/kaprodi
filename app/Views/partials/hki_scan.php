<?php /** @var string $scanUrl */ $scanUrl = $scanUrl ?? ''; ?>
<div class="mt-2">
    <button type="button" class="btn btn-sm btn-outline-info" id="btnScanHki">
        <i class="bi bi-magic"></i> Isi otomatis dari sertifikat
    </button>
    <span id="scanHkiStatus" class="small ms-2"></span>
    <div class="form-text">Pilih berkas PDF sertifikat di atas, lalu klik tombol ini. Teks dibaca langsung di browser Anda lalu field terisi otomatis — selalu periksa kembali hasilnya.</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" defer></script>
<script>
(function () {
    var btn = document.getElementById('btnScanHki');
    if (!btn) return;
    var fileInput = document.querySelector('input[name="link_bukti"]');
    var status = document.getElementById('scanHkiStatus');
    var csrfName = '<?= csrf_token() ?>';

    function say(msg, cls) { status.textContent = msg; status.className = 'small ms-2 text-' + cls; }
    function setVal(sel, val) { var el = document.querySelector(sel); if (el && val) el.value = val; }
    function setSelect(name, val) {
        var el = document.querySelector('select[name="' + name + '"]'); if (!el || !val) return;
        for (var i = 0; i < el.options.length; i++) { if (el.options[i].value === val) { el.selectedIndex = i; break; } }
    }

    // Ekstraksi teks di browser dengan PDF.js (tanpa dependensi server).
    async function extractClientSide(file) {
        if (!window.pdfjsLib) return null;
        try {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
            var buf = await file.arrayBuffer();
            var pdf = await pdfjsLib.getDocument({ data: buf }).promise;
            var max = Math.min(pdf.numPages, 3), parts = [];
            for (var p = 1; p <= max; p++) {
                var page = await pdf.getPage(p);
                var tc = await page.getTextContent();
                parts.push(tc.items.map(function (it) { return it.str; }).join(' '));
            }
            return parts.join('\n');
        } catch (e) { return null; }
    }

    function fill(d, csrfEl) {
        if (d.csrfHash && csrfEl) csrfEl.value = d.csrfHash;
        if (!d.ok) { say(d.error || 'Gagal membaca sertifikat.', 'danger'); return; }
        var f = d.fields || {};
        setVal('[name="judul"]', f.judul);
        setVal('[name="no_hki"]', f.no_hki);
        setVal('[name="tahun"]', f.tahun);
        setVal('[name="pemilik"]', f.pemilik);
        setSelect('jenis', f.jenis);
        setSelect('status', f.status);
        say('Data terisi otomatis. Mohon periksa & lengkapi sebelum menyimpan.', 'success');
    }

    btn.addEventListener('click', async function () {
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            say('Pilih berkas PDF sertifikat dulu.', 'danger'); return;
        }
        var file = fileInput.files[0];
        var csrfEl = document.querySelector('input[name="' + csrfName + '"]');
        btn.disabled = true; say('Membaca sertifikat…', 'muted');

        var teks = await extractClientSide(file);

        var fd = new FormData();
        if (csrfEl) fd.append(csrfName, csrfEl.value);
        if (teks && teks.replace(/\s/g, '').length >= 20) {
            fd.append('teks', teks);          // jalur utama (shared hosting)
        } else {
            fd.append('berkas', file);        // cadangan: minta server yang ekstrak
        }

        fetch('<?= $scanUrl ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) { btn.disabled = false; fill(d, csrfEl); })
            .catch(function () { btn.disabled = false; say('Gagal menghubungi server.', 'danger'); });
    });
})();
</script>
