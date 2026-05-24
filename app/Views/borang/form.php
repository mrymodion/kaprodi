<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi <?= esc($def['icon']) ?>"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> — <?= esc($def['title']) ?></h4>
    <a href="<?= site_url("borang/{$slug}") ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $isEdit ? site_url("borang/{$slug}/{$item['id']}/update") : site_url("borang/{$slug}/store") ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <?php foreach ($def['fields'] as $name => $f): ?>
                <?php $val = old($name, $item[$name] ?? ''); $req = !empty($f[2]); ?>
                <div class="col-md-<?= in_array($f[1], ['textarea', 'file']) ? 12 : 6 ?>">
                    <label class="form-label fw-semibold"><?= esc($f[0]) ?><?= $req ? ' <span class="text-danger">*</span>' : '' ?></label>
                    <?php if ($f[1] === 'file'): ?>
                        <?php if (!empty($val)): ?>
                        <div class="mb-1 small">
                            <?php if (\App\Services\EvidenceStorage::isStoredFile($val)): ?>
                                <a href="<?= site_url('eviden/file/' . $val) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti</a>
                            <?php else: ?>
                                <a href="<?= esc($val, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Bukti (link lama)</a>
                            <?php endif ?>
                            <label class="ms-2 text-danger"><input type="checkbox" name="hapus_<?= $name ?>" value="1"> hapus</label>
                        </div>
                        <?php endif ?>
                        <input type="file" name="<?= $name ?>" class="form-control" accept="application/pdf">
                        <small class="text-muted">Unggah PDF (maks <?= (int) env('storage.maxUploadMb', 20) ?> MB, dikompres bila Ghostscript ada).</small>
                    <?php elseif ($f[1] === 'textarea'): ?>
                        <?php if ($name === 'tupoksi'): ?>
                        <button type="button" class="btn btn-sm btn-outline-info mb-1 btn-ai-borang"
                            data-jenis="tupoksi" data-target="tupoksi"
                            data-ctx='{"unit":"unit_kerja","jabatan":"jabatan"}'><i class="bi bi-robot"></i> Bantu AI</button>
                        <?php endif ?>
                        <textarea name="<?= $name ?>" class="form-control" rows="3"><?= esc($val) ?></textarea>
                    <?php elseif ($f[1] === 'number'): ?>
                        <input type="number" name="<?= $name ?>" class="form-control" value="<?= esc($val) ?>">
                    <?php elseif ($f[1] === 'ta'): ?>
                        <select name="<?= $name ?>" class="form-select">
                            <option value="">—</option>
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= (int)$val === (int)$ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
                            <?php endforeach ?>
                        </select>
                    <?php elseif ($f[1] === 'select'): ?>
                        <select name="<?= $name ?>" class="form-select">
                            <?php if (!$req): ?><option value="">—</option><?php endif ?>
                            <?php foreach ($f[3] as $ov => $ol): ?>
                                <option value="<?= $ov ?>" <?= (string)$val === (string)$ov ? 'selected' : '' ?>><?= esc($ol) ?></option>
                            <?php endforeach ?>
                        </select>
                    <?php else: ?>
                        <input type="text" name="<?= $name ?>" class="form-control" value="<?= esc($val) ?>" <?= $req ? 'required' : '' ?>>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url("borang/{$slug}") ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= view('partials/ai_borang_script') ?>

<?= $this->endSection() ?>
