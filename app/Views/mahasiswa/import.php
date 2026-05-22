<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import Mahasiswa dari Excel</h4>
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php $res = session('importResult'); ?>
<?php if ($res): ?>
<div class="alert alert-<?= empty($res['errors']) ? 'success' : 'warning' ?>">
    <div class="fw-semibold mb-1"><i class="bi bi-check-circle"></i> Impor selesai:
        <?= (int) $res['inserted'] ?> baris baru, <?= (int) $res['updated'] ?> diperbarui,
        <?= count($res['errors']) ?> gagal.
    </div>
    <?php if (!empty($res['warnings'])): ?>
        <details class="mt-1"><summary class="small">Peringatan (<?= count($res['warnings']) ?>)</summary>
            <ul class="small mb-0"><?php foreach ($res['warnings'] as $w): ?><li><?= esc($w) ?></li><?php endforeach ?></ul>
        </details>
    <?php endif ?>
    <?php if (!empty($res['errors'])): ?>
        <details class="mt-1" open><summary class="small text-danger">Gagal (<?= count($res['errors']) ?>)</summary>
            <ul class="small mb-0"><?php foreach ($res['errors'] as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul>
        </details>
    <?php endif ?>
</div>
<?php endif ?>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Unggah File</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('mahasiswa/import') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel (.xlsx) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Data dimasukkan ke <strong>prodi yang sedang aktif</strong>. NIM yang sudah ada akan diperbarui (bukan duplikat).</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Impor</button>
                    <a href="<?= site_url('mahasiswa/import/template') ?>" class="btn btn-outline-success ms-2">
                        <i class="bi bi-download"></i> Unduh Template
                    </a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Petunjuk Kolom</div>
            <div class="card-body small">
                <ul class="mb-2">
                    <li><strong>Wajib:</strong> nim, nama</li>
                    <li><strong>jenis_kelamin:</strong> L atau P</li>
                    <li><strong>status:</strong> aktif, cuti, do, keluar, lulus (kosong = aktif)</li>
                    <li><strong>tanggal_lahir / tgl_lulus:</strong> format YYYY-MM-DD</li>
                    <li><strong>ipk:</strong> 0–4 (mis. 3.45)</li>
                    <li><strong>tahun_masuk:</strong> kode tahun akademik, mis.
                        <?php foreach (array_slice($taList, 0, 3) as $ta): ?>
                            <span class="badge bg-light text-dark border"><?= esc($ta['kode']) ?></span>
                        <?php endforeach ?>
                    </li>
                </ul>
                <div class="text-muted">Disarankan unduh template agar nama kolom sudah benar.</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
