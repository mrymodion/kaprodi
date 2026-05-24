<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $nama = trim(($dosen['gelar_depan'] ? $dosen['gelar_depan'].' ' : '').$dosen['nama'].($dosen['gelar_belakang'] ? ', '.$dosen['gelar_belakang'] : '')); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-circle"></i> Profil Saya</h4>
    <a href="<?= site_url('akun/profil') ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit Profil</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-1"><?= esc($nama) ?></h5>
        <div class="text-muted small">
            NIDN <?= esc($dosen['nidn'] ?? '-') ?> &middot; <?= esc($dosen['jabatan_akademik'] ?? 'Jabatan belum diisi') ?>
            &middot; <?= esc($dosen['pendidikan_terakhir'] ?? '-') ?>
        </div>
        <?php if (!empty($dosen['metrik_updated_at'])): ?>
        <div class="d-flex gap-3 mt-3 flex-wrap">
            <div><span class="fw-bold"><?= (int) $dosen['h_index'] ?></span> <span class="small text-muted">h-index</span></div>
            <div><span class="fw-bold"><?= (int) $dosen['sitasi_total'] ?></span> <span class="small text-muted">sitasi</span></div>
            <div><span class="fw-bold"><?= (int) $dosen['karya_total'] ?></span> <span class="small text-muted">karya (OpenAlex)</span></div>
        </div>
        <?php endif ?>
    </div>
</div>

<div class="row g-3 mb-3">
    <?php
    $cards = [
        ['Publikasi', $stats['publikasi'], 'bi-file-earmark-text', 'primary', site_url('akun/publikasi')],
        ['Penelitian (ketua)', $stats['penelitian'], 'bi-journal-text', 'success', site_url('akun/penelitian')],
        ['PkM (ketua)', $stats['pkm'], 'bi-megaphone', 'warning', site_url('akun/pkm')],
        ['Dokumen', $stats['dokumen'], 'bi-folder2-open', 'info', '#dokumen'],
    ];
    foreach ($cards as [$lbl, $val, $ic, $col, $url]): ?>
    <div class="col-6 col-md-3">
        <a href="<?= $url ?>" class="text-decoration-none">
        <div class="card h-100 text-center border-<?= $col ?>">
            <div class="card-body p-2">
                <i class="bi <?= $ic ?> fs-4 text-<?= $col ?>"></i>
                <div class="fs-4 fw-bold text-body"><?= $val ?></div>
                <div class="small text-muted"><?= $lbl ?></div>
            </div>
        </div>
        </a>
    </div>
    <?php endforeach ?>
</div>

<div class="alert alert-info small">
    <i class="bi bi-info-circle"></i> Lengkapi & perbarui data Anda agar borang akreditasi prodi akurat.
    Pastikan <strong>ORCID</strong> terisi agar metrik sitasi dapat disinkronkan otomatis.
    Disarankan segera <a href="<?= site_url('akun/password') ?>">ganti password</a> bila ini login pertama Anda.
</div>

<?= view('dokumen/_panel', ['ref' => 'dosen', 'ownerId' => $dosen['id'], 'dokumen' => $dokumen, 'jenisOpt' => $jenisOpt]) ?>

<?= $this->endSection() ?>
