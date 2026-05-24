<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $jenisLabels = \App\Models\PublikasiModel::JENIS_LABELS; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-search"></i> Cari Publikasi (OpenAlex)</h4>
    <a href="<?= site_url('publikasi') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="alert alert-info small">
    <i class="bi bi-info-circle"></i> Sumber data: <strong>OpenAlex</strong> (gratis, terbuka). Pencarian berdasarkan <strong>ORCID</strong> jauh lebih akurat daripada nama.
    Hasil adalah <em>alat bantu impor</em> — harap verifikasi setiap publikasi (kepenulisan & jenis) sebelum & sesudah impor.
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold small">Pilih Dosen (mengisi otomatis)</label>
                <select id="selDosen" class="form-select">
                    <option value="">— Pilih dosen —</option>
                    <?php foreach ($dosen as $d): ?>
                    <option value="<?= esc($d['orcid'] ?? '', 'attr') ?>" data-nama="<?= esc($d['nama'], 'attr') ?>">
                        <?= esc($d['nama']) ?><?= !empty($d['orcid']) ? ' (ORCID)' : '' ?>
                    </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">ORCID</label>
                <input type="text" id="inOrcid" class="form-control" placeholder="0000-0000-0000-0000">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold small">atau Nama</label>
                <input type="text" id="inNama" class="form-control" placeholder="Nama penulis">
            </div>
            <div class="col-md-2">
                <button id="btnCari" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
            </div>
        </div>
    </div>
</div>

<form method="post" action="<?= site_url('publikasi/cari/import') ?>" id="formImport">
    <?= csrf_field() ?>
    <div id="hasilWrap" class="card d-none">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Hasil Pencarian (<span id="hasilCount">0</span>)</span>
            <div>
                <button type="button" id="btnSelAll" class="btn btn-sm btn-outline-secondary">Pilih semua</button>
                <button type="submit" id="btnImport" class="btn btn-sm btn-success" disabled><i class="bi bi-download"></i> Impor Terpilih</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:34px"></th>
                        <th>Judul / Penulis</th>
                        <th>Media</th>
                        <th class="text-center">Tahun</th>
                        <th class="text-center">Sitasi</th>
                        <th>Jenis</th>
                    </tr>
                </thead>
                <tbody id="hasilBody"></tbody>
            </table>
        </div>
    </div>
    <div id="emptyMsg" class="text-center text-muted py-5 d-none">Tidak ada publikasi ditemukan.</div>
</form>

<template id="rowTpl">
    <tr>
        <td class="text-center"><input type="checkbox" class="form-check-input chk"></td>
        <td>
            <span class="j fw-semibold"></span>
            <br><small class="p text-muted"></small>
            <small class="doi d-block"></small>
        </td>
        <td><small class="m"></small></td>
        <td class="text-center th"></td>
        <td class="text-center s"></td>
        <td>
            <select class="form-select form-select-sm jenis">
                <?php foreach ($jenisLabels as $v => $l): ?>
                <option value="<?= $v ?>"><?= esc($l) ?></option>
                <?php endforeach ?>
            </select>
        </td>
    </tr>
</template>

<script>
(function () {
    var sel = document.getElementById('selDosen');
    var inOrcid = document.getElementById('inOrcid');
    var inNama = document.getElementById('inNama');
    var btnCari = document.getElementById('btnCari');
    var wrap = document.getElementById('hasilWrap');
    var body = document.getElementById('hasilBody');
    var emptyMsg = document.getElementById('emptyMsg');
    var btnImport = document.getElementById('btnImport');
    var btnSelAll = document.getElementById('btnSelAll');
    var tpl = document.getElementById('rowTpl');

    sel.addEventListener('change', function () {
        var opt = sel.options[sel.selectedIndex];
        inOrcid.value = sel.value || '';
        inNama.value = sel.value ? '' : (opt.dataset.nama || '');
    });

    function refreshImportBtn() {
        var any = body.querySelectorAll('.chk:checked').length > 0;
        btnImport.disabled = !any;
    }

    btnSelAll.addEventListener('click', function () {
        var boxes = body.querySelectorAll('.chk:not([disabled])');
        var allOn = Array.prototype.every.call(boxes, function (b) { return b.checked; });
        boxes.forEach(function (b) { b.checked = !allOn; });
        refreshImportBtn();
    });

    btnCari.addEventListener('click', function () {
        var orig = btnCari.innerHTML;
        btnCari.disabled = true; btnCari.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        var fd = new URLSearchParams();
        fd.append('orcid', inOrcid.value.trim());
        fd.append('nama', inNama.value.trim());
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        fetch('<?= site_url('publikasi/cari/search') ?>', {
            method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: fd
        }).then(function (r) { return r.json(); }).then(function (d) {
            body.innerHTML = '';
            if (!d || !d.ok) { alert(d && d.error ? d.error : 'Pencarian gagal.'); return; }
            if (d.csrfName && d.csrfHash) {
                var f = document.querySelector('input[name="' + d.csrfName + '"]');
                if (f) f.value = d.csrfHash;
            }
            wrap.classList.toggle('d-none', d.items.length === 0);
            emptyMsg.classList.toggle('d-none', d.items.length !== 0);
            document.getElementById('hasilCount').textContent = d.items.length;
            d.items.forEach(function (it, i) {
                var node = tpl.content.cloneNode(true);
                var tr = node.querySelector('tr');
                node.querySelector('.j').textContent = it.judul;
                node.querySelector('.p').textContent = it.penulis || '—';
                node.querySelector('.m').textContent = it.nama_media || '—';
                node.querySelector('.th').textContent = it.tahun || '—';
                node.querySelector('.s').textContent = it.sitasi || 0;
                var doiEl = node.querySelector('.doi');
                if (it.doi) {
                    doiEl.innerHTML = '<a href="https://doi.org/' + it.doi + '" target="_blank" rel="noopener">' + it.doi + '</a>';
                }
                node.querySelector('.jenis').value = it.jenis;
                var pfx = 'rows[' + i + ']';
                var chk = node.querySelector('.chk');
                chk.name = pfx + '[sel]';
                chk.value = '1';
                if (it.exists) {
                    chk.disabled = true;
                    tr.classList.add('table-warning');
                    doiEl.innerHTML += ' <span class="badge bg-warning text-dark">sudah ada</span>';
                }
                // Field data ikut terkirim untuk semua baris; server hanya mengimpor baris ber-[sel].
                addHidden(tr, pfx + '[judul]', it.judul);
                addHidden(tr, pfx + '[penulis]', it.penulis || '');
                addHidden(tr, pfx + '[nama_media]', it.nama_media || '');
                addHidden(tr, pfx + '[tahun]', it.tahun || '');
                addHidden(tr, pfx + '[volume]', it.volume || '');
                addHidden(tr, pfx + '[doi]', it.doi || '');
                addHidden(tr, pfx + '[url]', it.url || '');
                addHidden(tr, pfx + '[sitasi]', it.sitasi || 0);
                var jSelect = node.querySelector('.jenis');
                jSelect.name = pfx + '[jenis]';
                chk.addEventListener('change', refreshImportBtn);
                body.appendChild(node);
            });
            refreshImportBtn();
        }).catch(function () { alert('Gagal menghubungi server.'); })
          .finally(function () { btnCari.disabled = false; btnCari.innerHTML = orig; });
    });

    function addHidden(tr, name, val) {
        var h = document.createElement('input');
        h.type = 'hidden'; h.name = name; h.value = val;
        tr.appendChild(h);
        return h;
    }
})();
</script>

<?= $this->endSection() ?>
