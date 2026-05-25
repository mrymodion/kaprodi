<?php
/**
 * Tombol "Isi otomatis" generik untuk form dengan bukti PDF.
 * Membaca teks PDF di browser (PDF.js), kirim ke $scanUrl, lalu mengisi
 * field berdasarkan nama (key pada `fields` → elemen [name="key"]).
 *
 * @var string $scanUrl
 * @var string $fileName  nama input file (default: link_bukti)
 */
$fileName = $fileName ?? 'link_bukti';
$uid = 'docscan_' . substr(md5($scanUrl . $fileName), 0, 8);
?>
<div class="mt-2">
    <button type="button" class="btn btn-sm btn-outline-info" id="btn_<?= $uid ?>">
        <i class="bi bi-magic"></i> Isi otomatis dari berkas
    </button>
    <span id="st_<?= $uid ?>" class="small ms-2"></span>
    <div class="form-text">Pilih berkas PDF di atas, lalu klik tombol ini. Teks dibaca langsung di browser Anda lalu field terisi otomatis — selalu periksa kembali hasilnya.</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js" defer></script>
<script>
(function () {
    var btn = document.getElementById('btn_<?= $uid ?>');
    if (!btn) return;
    var form = btn.closest('form');
    var fileInput = form.querySelector('input[name="<?= esc($fileName, 'js') ?>"]');
    var status = document.getElementById('st_<?= $uid ?>');
    var csrfName = '<?= csrf_token() ?>';

    function say(msg, cls) { status.textContent = msg; status.className = 'small ms-2 text-' + cls; }

    function setField(name, val) {
        if (val === undefined || val === null || val === '') return;
        var el = form.querySelector('[name="' + name + '"]');
        if (!el) return;
        if (el.tagName === 'SELECT') {
            for (var i = 0; i < el.options.length; i++) {
                if (el.options[i].value === String(val)) { el.selectedIndex = i; el.dispatchEvent(new Event('change')); return; }
            }
        } else {
            el.value = val;
        }
    }

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
        if (!d.ok) { say(d.error || 'Gagal membaca berkas.', 'danger'); return; }
        var f = d.fields || {};
        Object.keys(f).forEach(function (k) { setField(k, f[k]); });
        say('Data terisi otomatis. Mohon periksa & lengkapi sebelum menyimpan.', 'success');
    }

    btn.addEventListener('click', async function () {
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            say('Pilih berkas PDF dulu.', 'danger'); return;
        }
        var file = fileInput.files[0];
        var csrfEl = form.querySelector('input[name="' + csrfName + '"]');
        btn.disabled = true; say('Membaca berkas…', 'muted');

        var teks = await extractClientSide(file);

        var fd = new FormData();
        if (csrfEl) fd.append(csrfName, csrfEl.value);
        if (teks && teks.replace(/\s/g, '').length >= 20) {
            fd.append('teks', teks);
        } else {
            fd.append('berkas', file);
        }

        fetch('<?= $scanUrl ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) { btn.disabled = false; fill(d, csrfEl); })
            .catch(function () { btn.disabled = false; say('Gagal menghubungi server.', 'danger'); });
    });
})();
</script>
