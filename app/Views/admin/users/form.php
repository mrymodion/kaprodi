<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h3><?= $mode === 'create' ? 'Tambah User' : 'Edit User' ?></h3>

<form method="post" action="<?= $mode === 'create' ? site_url('admin/users') : site_url('admin/users/'.$user->id.'/update') ?>" class="card card-body shadow-sm">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-4"><label class="form-label">Username</label>
            <input class="form-control" name="username" required value="<?= esc($user->username ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" required value="<?= esc($user->email ?? '') ?>"></div>
        <div class="col-md-4"><label class="form-label">Password <?= $mode==='edit' ? '(kosongkan jika tidak diubah)' : '' ?></label>
            <input type="password" class="form-control" name="password" <?= $mode==='create' ? 'required' : '' ?>></div>
    </div>

    <hr>
    <label class="form-label"><strong>Role / Group</strong></label>
    <div class="row">
        <?php foreach ($groups as $key => $g): ?>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="groups[]" value="<?= $key ?>" id="g_<?= $key ?>"
                        <?= isset($currentGroups) && in_array($key, $currentGroups) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="g_<?= $key ?>">
                        <strong><?= esc($g['title']) ?></strong>
                        <small class="d-block text-muted"><?= esc($g['description']) ?></small>
                    </label>
                </div>
            </div>
        <?php endforeach ?>
    </div>

    <hr>
    <label class="form-label"><strong>Mapping Prodi, Peran & Dosen</strong> (baris pertama = default)</label>
    <div class="row g-2 mb-1 small text-muted d-none d-md-flex">
        <div class="col-md-5">Prodi</div><div class="col-md-3">Peran</div><div class="col-md-4">Dosen DTPR (untuk peran kaprodi/dosen)</div>
    </div>
    <?php $linkedProdiId = 0; foreach ($dosenAll as $da) { if ((int) $da['id'] === (int) $linkedDosenId) { $linkedProdiId = (int) $da['prodi_id']; break; } } ?>
    <div id="prodiRows">
        <?php $rows = $mapping ?: [['prodi_id'=>'','peran'=>'']]; foreach ($rows as $i => $r): $rowProdi = (int) ($r['prodi_id'] ?? 0); $rowDosen = ($rowProdi && $rowProdi === $linkedProdiId) ? (int) $linkedDosenId : 0; ?>
            <div class="row g-2 mb-2">
                <div class="col-md-5">
                    <select name="prodi[]" class="form-select prodi-sel">
                        <option value="">-- pilih prodi --</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $rowProdi == $p['id'] ?'selected':'' ?>>
                                <?= esc($p['nama']) ?> (<?= $p['jenjang'] ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="peran[]" class="form-select">
                        <?php foreach (array_keys($groups) as $g): ?>
                            <option value="<?= $g ?>" <?= isset($r['peran']) && $r['peran']==$g?'selected':'' ?>><?= $g ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="dosen[]" class="form-select dosen-sel" data-current="<?= $rowDosen ?>">
                        <option value="0">— (tidak ditautkan) —</option>
                    </select>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <button type="button" class="btn btn-sm btn-secondary" onclick="addRow()"><i class="bi bi-plus"></i> Tambah Mapping</button>

    <hr>
    <div>
        <button class="btn btn-primary"><i class="bi bi-check"></i> Simpan</button>
        <a href="<?= site_url('admin/users') ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<script>
var DOSEN = <?= json_encode(array_map(fn($d) => ['id' => (int) $d['id'], 'prodi' => (int) $d['prodi_id'], 'label' => $d['label']], $dosenAll), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

function fillDosen(row) {
    var prodiSel = row.querySelector('.prodi-sel');
    var dosenSel = row.querySelector('.dosen-sel');
    if (!prodiSel || !dosenSel) return;
    var pid = parseInt(prodiSel.value || '0', 10);
    var current = parseInt(dosenSel.getAttribute('data-current') || dosenSel.value || '0', 10);
    dosenSel.innerHTML = '<option value="0">— (tidak ditautkan) —</option>';
    DOSEN.filter(function (d) { return d.prodi === pid; }).forEach(function (d) {
        var o = document.createElement('option');
        o.value = d.id; o.textContent = d.label;
        if (d.id === current) o.selected = true;
        dosenSel.appendChild(o);
    });
}

function bindRow(row) {
    var prodiSel = row.querySelector('.prodi-sel');
    if (prodiSel) prodiSel.addEventListener('change', function () {
        var ds = row.querySelector('.dosen-sel');
        if (ds) ds.setAttribute('data-current', '0'); // ganti prodi → reset tautan
        fillDosen(row);
    });
    fillDosen(row);
}

function addRow() {
    const tmpl = document.querySelector('#prodiRows .row');
    const clone = tmpl.cloneNode(true);
    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    var ds = clone.querySelector('.dosen-sel');
    if (ds) ds.setAttribute('data-current', '0');
    document.getElementById('prodiRows').appendChild(clone);
    bindRow(clone);
}

document.querySelectorAll('#prodiRows .row').forEach(bindRow);
</script>
<?= $this->endSection() ?>
