<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $canManage = auth()->user()->can('kurikulum.manage'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-book"></i> Kurikulum & OBE</h4>
    <?php if ($canManage): ?>
        <a href="<?= site_url('kurikulum/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Kurikulum
        </a>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<?php if (empty($kurikulum)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Belum ada kurikulum untuk prodi ini.
        <?php if (auth()->user()->can('kurikulum.manage')): ?>
            <a href="<?= site_url('kurikulum/create') ?>">Tambah sekarang</a>.
        <?php endif ?>
    </div>
<?php else: ?>
<div class="row g-3">
    <?php foreach ($kurikulum as $k): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card h-100 <?= $k['aktif'] ? 'border-primary' : 'opacity-75' ?>">
            <div class="card-header d-flex justify-content-between align-items-center <?= $k['aktif'] ? 'bg-primary text-white' : 'bg-light' ?>">
                <span class="fw-semibold"><?= esc($k['kode']) ?></span>
                <?= $k['aktif'] ? '<span class="badge bg-light text-primary">Aktif</span>' : '<span class="badge bg-secondary">Non-aktif</span>' ?>
            </div>
            <div class="card-body">
                <h6 class="card-title"><?= esc($k['nama']) ?></h6>
                <div class="text-muted small mb-1">Berlaku sejak <?= esc($k['tahun_berlaku']) ?></div>
                <?php if (!empty($k['nama_prodi'])): ?>
                    <div class="text-muted small mb-3"><i class="bi bi-building"></i> <?= esc($k['nama_prodi']) ?></div>
                <?php else: ?><div class="mb-3"></div><?php endif ?>
                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-success"><?= $k['jml_cpl'] ?? 0 ?></div>
                            <div class="small text-muted">CPL</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border rounded p-2">
                            <div class="fs-4 fw-bold text-primary"><?= $k['jml_mk'] ?? 0 ?></div>
                            <div class="small text-muted">Mata Kuliah</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white d-flex gap-2">
                <a href="<?= site_url("kurikulum/{$k['id']}") ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                    <i class="bi bi-eye"></i> Kelola
                </a>
                <?php if (auth()->user()->can('kurikulum.manage')): ?>
                    <a href="<?= site_url("kurikulum/{$k['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="post" action="<?= site_url("kurikulum/{$k['id']}/delete") ?>" class="d-inline"
                          onsubmit="return confirm('Nonaktifkan kurikulum ini?')">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-archive"></i></button>
                    </form>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>

<?= $this->endSection() ?>
