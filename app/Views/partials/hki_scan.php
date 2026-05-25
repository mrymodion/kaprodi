<?php /** @var string $scanUrl */ $scanUrl = $scanUrl ?? ''; ?>
<div class="mt-2">
    <button type="button" class="btn btn-sm btn-outline-info" id="btnScanHki">
        <i class="bi bi-magic"></i> Isi otomatis dari sertifikat
    </button>
    <span id="scanHkiStatus" class="small ms-2"></span>
    <div class="form-text">Pilih berkas PDF sertifikat di atas, lalu klik tombol ini untuk mengisi field secara otomatis. Selalu periksa kembali hasilnya.</div>
</div>
<script>
(function () {
    var btn = document.getElementById('btnScanHki');
    if (!btn) return;
    var fileInput = document.querySelector('input[name="link_bukti"]');
    var status = document.getElementById('scanHkiStatus');
    var csrfName = '<?= csrf_token() ?>';
    function setVal(sel, val) { var el = document.querySelector(sel); if (el && val) el.value = val; }
    function setSelect(name, val) {
        var el = document.querySelector('select[name="' + name + '"]'); if (!el || !val) return;
        for (var i = 0; i < el.options.length; i++) { if (el.options[i].value === val) { el.selectedIndex = i; break; } }
    }
    btn.addEventListener('click', function () {
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
            status.textContent = 'Pilih berkas PDF sertifikat dulu.'; status.className = 'small ms-2 text-danger'; return;
        }
        var fd = new FormData();
        fd.append('berkas', fileInput.files[0]);
        var csrfEl = document.querySelector('input[name="' + csrfName + '"]');
        if (csrfEl) fd.append(csrfName, csrfEl.value);
        btn.disabled = true; status.textContent = 'Membaca sertifikat…'; status.className = 'small ms-2 text-muted';
        fetch('<?= $scanUrl ?>', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                btn.disabled = false;
                if (d.csrfHash && csrfEl) csrfEl.value = d.csrfHash;
                if (!d.ok) { status.textContent = d.error || 'Gagal membaca sertifikat.'; status.className = 'small ms-2 text-danger'; return; }
                var f = d.fields || {};
                setVal('[name="judul"]', f.judul);
                setVal('[name="no_hki"]', f.no_hki);
                setVal('[name="tahun"]', f.tahun);
                setVal('[name="pemilik"]', f.pemilik);
                setSelect('jenis', f.jenis);
                setSelect('status', f.status);
                status.textContent = 'Data terisi otomatis. Mohon periksa & lengkapi sebelum menyimpan.';
                status.className = 'small ms-2 text-success';
            })
            .catch(function () { btn.disabled = false; status.textContent = 'Gagal menghubungi server.'; status.className = 'small ms-2 text-danger'; });
    });
})();
</script>
