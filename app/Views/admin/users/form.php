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
    <label class="form-label"><strong>Mapping Prodi & Peran</strong> (baris pertama = default)</label>
    <div id="prodiRows">
        <?php $rows = $mapping ?: [['prodi_id'=>'','peran'=>'']]; foreach ($rows as $i => $r): ?>
            <div class="row g-2 mb-2">
                <div class="col-md-7">
                    <select name="prodi[]" class="form-select">
                        <option value="">-- pilih prodi --</option>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= isset($r['prodi_id']) && $r['prodi_id']==$p['id']?'selected':'' ?>>
                                <?= esc($p['nama']) ?> (<?= $p['jenjang'] ?>)
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="peran[]" class="form-select">
                        <?php foreach (array_keys($groups) as $g): ?>
                            <option value="<?= $g ?>" <?= isset($r['peran']) && $r['peran']==$g?'selected':'' ?>><?= $g ?></option>
                        <?php endforeach ?>
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
function addRow() {
    const tmpl = document.querySelector('#prodiRows .row');
    const clone = tmpl.cloneNode(true);
    clone.querySelectorAll('select').forEach(s => s.selectedIndex = 0);
    document.getElementById('prodiRows').appendChild(clone);
}
</script>
<?= $this->endSection() ?>
