<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($kuri) ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-book"></i> <?= $isEdit ? 'Edit Kurikulum' : 'Tambah Kurikulum' ?></h4>
    <a href="<?= site_url('kurikulum') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card" style="max-width:640px">
    <div class="card-header bg-primary text-white fw-semibold">Data Kurikulum</div>
    <div class="card-body">
        <form method="post" action="<?= esc($action) ?>">
            <?= csrf_field() ?>
            <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>

            <div class="mb-3">
                <label class="form-label">Prodi <span class="text-danger">*</span></label>
                <select name="prodi_id" class="form-select" required>
                    <option value="">— Pilih —</option>
                    <?php foreach ($prodiList as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= old('prodi_id', $kuri['prodi_id'] ?? session('active_prodi_id')) == $p['id'] ? 'selected' : '' ?>>
                            <?= esc($p['nama']) ?> (<?= esc($p['jenjang']) ?>)
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Kode Kurikulum <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control" required maxlength="30"
                        placeholder="mis. K-2022"
                        value="<?= esc(old('kode', $kuri['kode'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun Berlaku <span class="text-danger">*</span></label>
                    <input type="number" name="tahun_berlaku" class="form-control" required
                        min="2000" max="2099" placeholder="2022"
                        value="<?= esc(old('tahun_berlaku', $kuri['tahun_berlaku'] ?? date('Y'))) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Status</label>
                    <select name="aktif" class="form-select">
                        <option value="1" <?= old('aktif', $kuri['aktif'] ?? 1) == 1 ? 'selected' : '' ?>>Aktif</option>
                        <option value="0" <?= old('aktif', $kuri['aktif'] ?? 1) == 0 ? 'selected' : '' ?>>Non-aktif</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label">Nama Kurikulum <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required
                    placeholder="mis. Kurikulum OBE 2022 Berbasis MBKM"
                    value="<?= esc(old('nama', $kuri['nama'] ?? '')) ?>">
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-floppy"></i> <?= $isEdit ? 'Simpan' : 'Tambah' ?>
                </button>
                <a href="<?= site_url('kurikulum') ?>" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
