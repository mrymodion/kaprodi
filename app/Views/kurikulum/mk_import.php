<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import Mata Kuliah dari Excel</h4>
    <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-mk") ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
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
                <form method="post" action="<?= site_url("kurikulum/{$kuri['id']}/mk/import") ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel (.xlsx) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Kode MK yang sudah ada akan diperbarui. Kolom <code>cpl</code> memetakan MK ke CPL (pakai kode CPL yang sudah ada di kurikulum ini).</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Impor</button>
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/mk/import/template") ?>" class="btn btn-outline-success ms-2"><i class="bi bi-download"></i> Unduh Template</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-header bg-white fw-semibold">Petunjuk</div>
            <div class="card-body small">
                <ul class="mb-0">
                    <li><strong>Wajib:</strong> kode, nama, semester</li>
                    <li><strong>jenis:</strong> wajib / pilihan / mbkm</li>
                    <li><strong>cpl:</strong> daftar kode CPL dipisah <code>;</code> atau <code>,</code> (mis. <code>CPL-01; CPL-03</code>)</li>
                    <li>Tip: impor <strong>CPL dulu</strong>, baru Mata Kuliah agar pemetaan CPL berhasil.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
