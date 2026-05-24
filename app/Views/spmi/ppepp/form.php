<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-arrow-repeat"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Siklus PPEPP</h4>
    <a href="<?= site_url('spmi/ppepp') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $isEdit ? site_url("spmi/ppepp/{$item['id']}/update") : site_url('spmi/ppepp/baru') ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Siklus <span class="text-danger">*</span></label>
                <input type="text" name="siklus" class="form-control" placeholder="mis. 2024/2025" value="<?= esc(old('siklus', $item['siklus'] ?? '')) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahap <span class="text-danger">*</span></label>
                <select name="tahap" class="form-select">
                    <?php foreach ($tahap as $k => $v): ?>
                        <option value="<?= $k ?>" <?= old('tahap', $item['tahap'] ?? '') === $k ? 'selected' : '' ?>><?= esc($v) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Judul / Fokus <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" placeholder="mis. Penetapan Standar Penelitian" value="<?= esc(old('judul', $item['judul'] ?? '')) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Uraian / Isi</label>
                <textarea name="isi" class="form-control" rows="5" placeholder="Uraikan kegiatan pada tahap ini..."><?= esc(old('isi', $item['isi'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Rencana/realisasi tindak lanjut..."><?= esc(old('tindak_lanjut', $item['tindak_lanjut'] ?? '')) ?></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('spmi/ppepp') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
