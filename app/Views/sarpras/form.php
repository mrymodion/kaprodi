<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-building-gear"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Sarana/Prasarana</h4>
    <a href="<?= site_url('sarpras') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold">Nama Sarana/Prasarana <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required value="<?= esc(old('nama', $item['nama'] ?? '')) ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" class="form-select">
                    <?php foreach ($kategori as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('kategori', $item['kategori'] ?? 'ruang_kelas')===$v?'selected':'' ?>><?= esc($l) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Jumlah</label>
                <input type="number" min="1" name="jumlah" class="form-control" value="<?= esc(old('jumlah', $item['jumlah'] ?? 1)) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Luas (m²)</label>
                <input type="number" step="0.01" name="luas_m2" class="form-control" value="<?= esc(old('luas_m2', $item['luas_m2'] ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kapasitas</label>
                <input type="number" name="kapasitas" class="form-control" value="<?= esc(old('kapasitas', $item['kapasitas'] ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kondisi</label>
                <select name="kondisi" class="form-select">
                    <?php foreach (['baik'=>'Baik','rusak_ringan'=>'Rusak Ringan','rusak_berat'=>'Rusak Berat'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('kondisi', $item['kondisi'] ?? 'baik')===$v?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kepemilikan</label>
                <select name="kepemilikan" class="form-select">
                    <?php foreach (['milik'=>'Milik Sendiri','sewa'=>'Sewa','pinjam'=>'Pinjam'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('kepemilikan', $item['kepemilikan'] ?? 'milik')===$v?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-9">
                <label class="form-label fw-semibold d-block">Digunakan untuk</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="untuk_pendidikan" value="1" id="up" <?= old('untuk_pendidikan', $item['untuk_pendidikan'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="up">Pendidikan (5.2)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="untuk_penelitian" value="1" id="ur" <?= old('untuk_penelitian', $item['untuk_penelitian'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="ur">Penelitian (3.A.1)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="untuk_pkm" value="1" id="uk" <?= old('untuk_pkm', $item['untuk_pkm'] ?? 0) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="uk">PkM (4.A.1)</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2"><?= esc(old('keterangan', $item['keterangan'] ?? '')) ?></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('sarpras') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
