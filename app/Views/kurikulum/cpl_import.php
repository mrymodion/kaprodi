<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import CPL dari Excel</h4>
    <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-cpl") ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<p class="text-muted small">Kurikulum: <strong><?= esc($kuri['nama']) ?></strong></p>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php $res = session('importResult'); if ($res): ?>
<div class="alert alert-<?= empty($res['errors']) ? 'success' : 'warning' ?>">
    <div class="fw-semibold mb-1"><i class="bi bi-check-circle"></i> Impor selesai:
        <?= (int) $res['inserted'] ?> baru, <?= (int) $res['updated'] ?> diperbarui, <?= count($res['errors']) ?> gagal.</div>
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
                <form method="post" action="<?= site_url("kurikulum/{$kuri['id']}/cpl/import") ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel (.xlsx) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Kode CPL yang sudah ada akan diperbarui (tidak duplikat).</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Impor</button>
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/cpl/import/template") ?>" class="btn btn-outline-success ms-2"><i class="bi bi-download"></i> Unduh Template</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-header bg-white fw-semibold">Petunjuk</div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li><strong>Wajib:</strong> kode, aspek, deskripsi</li>
                    <li><strong>aspek:</strong> sikap, pengetahuan, keterampilan_umum, keterampilan_khusus</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
