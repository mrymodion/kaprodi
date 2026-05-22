<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-excel"></i> Import EWMP dari Excel</h4>
    <a href="<?= site_url('dosen/ewmp') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php $res = session('importResult'); if ($res): ?>
<div class="alert alert-<?= empty($res['errors']) ? 'success' : 'warning' ?>">
    <div class="fw-semibold mb-1"><i class="bi bi-check-circle"></i> Impor selesai:
        <?= (int) $res['inserted'] ?> EWMP disimpan, <?= count($res['errors']) ?> gagal.</div>
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
                <form method="post" action="<?= site_url('dosen/ewmp/import') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">File Excel (.xlsx) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
                        <small class="text-muted">Setiap baris = beban EWMP satu dosen pada satu tahun akademik. Total SKS dihitung otomatis. Dosen dikenali dari <strong>NIDN</strong> di prodi aktif.</small>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-upload"></i> Impor</button>
                    <a href="<?= site_url('dosen/ewmp/import/template') ?>" class="btn btn-outline-success ms-2"><i class="bi bi-download"></i> Unduh Template</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card"><div class="card-header bg-white fw-semibold">Petunjuk</div>
            <div class="card-body small">
                <ul class="mb-2">
                    <li><strong>nidn:</strong> harus sudah terdaftar sebagai dosen prodi aktif</li>
                    <li><strong>tahun_akademik:</strong> kode TA, mis.
                        <?php foreach (array_slice($taList, 0, 3) as $ta): ?>
                            <span class="badge bg-light text-dark border"><?= esc($ta['kode']) ?></span>
                        <?php endforeach ?>
                    </li>
                    <li>Kolom SKS: pendidikan_ps, ps_lain_pt, pt_lain, penelitian, pkm, tugas_tambahan</li>
                    <li>EWMP normal <strong>12–16 SKS</strong> per semester</li>
                </ul>
                <div class="text-muted">Impor EWMP yang sama akan memperbarui data (bukan menambah ganda).</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
