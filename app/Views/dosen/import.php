<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import DTPR (Dosen) dari Excel</h4>
    <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php $res = session('importResult'); if ($res): ?>
<div class="alert alert-<?= empty($res['errors']) ? 'success' : 'warning' ?>">
    <div class="fw-semibold mb-1"><i class="bi bi-check-circle"></i> Impor selesai:
        <?= (int) $res['inserted'] ?> baru, <?= (int) $res['updated'] ?> diperbarui, <?= count($res['errors']) ?> gagal.</div>
    <?php if (!empty($res['warnings'])): ?>
        <details class="mt-1"><summary class="small">Peringatan (<?= count($res['warnings']) ?>)</summary>
            <ul class="small mb-0"><?php foreach ($res['warnings'] as $w): ?><li><?= esc($w) ?></li><?php endforeach ?></ul></details>
    <?php endif ?>
    <?php if (!empty($res['errors'])): ?>
        <details class="mt-1" open><summary class="small text-danger">Gagal (<?= count($res['errors']) ?>)</summary>
            <ul class="small mb-0"><?php foreach ($res['errors'] as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></details>
    <?php endif ?>
</div>
<?php endif ?>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card"><div class="card-header bg-white fw-semibold">Unggah File</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('dosen/import') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel (.xlsx) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Data masuk ke <strong>prodi aktif</strong>. Dosen ber-NIDN yang sudah ada akan diperbarui; NIDN milik prodi lain ditolak (kaidah homebase).</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Impor</button>
                    <a href="<?= site_url('dosen/import/template') ?>" class="btn btn-outline-success ms-2"><i class="bi bi-download"></i> Unduh Template</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-header bg-white fw-semibold">Petunjuk Kolom</div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li><strong>Wajib:</strong> nama (nidn sangat disarankan)</li>
                    <li><strong>nidn:</strong> 10 digit angka</li>
                    <li><strong>jenis_kelamin:</strong> L / P</li>
                    <li><strong>pendidikan_terakhir:</strong> S1/S2/S3/Sp/Sp2 (DTPR min. S2)</li>
                    <li><strong>linieritas:</strong> linier / tidak_linier</li>
                    <li><strong>jabatan_akademik:</strong> Tenaga Pengajar, Asisten Ahli, Lektor, Lektor Kepala, Guru Besar</li>
                    <li><strong>sertifikat_pendidik:</strong> ya/1 atau tidak/0</li>
                    <li><strong>status_kepegawaian:</strong> tetap / tidak_tetap / dosen_industri</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
