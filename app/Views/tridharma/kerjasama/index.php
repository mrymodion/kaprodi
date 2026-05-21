<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $canManage = auth()->user()->can('kerjasama.manage'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-link-45deg"></i> Kerjasama</h4>
    <?php if ($canManage): ?>
    <a href="<?= site_url('kerjasama/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Kerjasama</a>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<!-- Summary -->
<?php if ($summary): ?>
<div class="row g-3 mb-3">
    <div class="col-md-2">
        <div class="card border-primary text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-primary"><?= $summary['total'] ?></div>
                <div class="small text-muted">Total MoU/MoA</div>
            </div>
        </div>
    </div>
    <?php foreach (['lokal'=>'Lokal','wilayah'=>'Wilayah','nasional'=>'Nasional','internasional'=>'Internasional'] as $k=>$l): ?>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold"><?= $summary['per_tingkat'][$k] ?? 0 ?></div>
                <div class="small text-muted"><?= $l ?></div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>

<!-- Filter -->
<form class="row g-2 mb-3" method="get">
    <div class="col-auto">
        <select name="bidang" class="form-select form-select-sm">
            <option value="">Semua Bidang</option>
            <?php foreach (['pendidikan'=>'Pendidikan','penelitian'=>'Penelitian','pkm'=>'PkM','tridharma'=>'Tridharma'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= $bidang===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        <a href="<?= site_url('kerjasama') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Mitra</th>
                        <th>Bidang</th>
                        <th>Tingkat</th>
                        <th>Jenis Dokumen</th>
                        <th>Tgl Mulai</th>
                        <th>Tgl Berakhir</th>
                        <th>Status</th>
                        <?php if ($canManage): ?><th></th><?php endif ?>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data kerjasama.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $k): ?>
                <?php
                $today = date('Y-m-d');
                $aktif = $k['tgl_mulai'] <= $today && (!$k['tgl_berakhir'] || $k['tgl_berakhir'] >= $today);
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <?= esc($k['nama_mitra']) ?>
                        <?php if ($k['no_dokumen']): ?><br><small class="text-muted"><?= esc($k['no_dokumen']) ?></small><?php endif ?>
                    </td>
                    <td><?= ucfirst($k['bidang']) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($k['tingkat']) ?></span></td>
                    <td><?= strtoupper($k['jenis_dokumen']) ?></td>
                    <td><?= esc($k['tgl_mulai']) ?></td>
                    <td><?= $k['tgl_berakhir'] ? esc($k['tgl_berakhir']) : '<span class="text-muted">—</span>' ?></td>
                    <td><span class="badge <?= $aktif ? 'bg-success' : 'bg-secondary' ?>"><?= $aktif ? 'Aktif' : 'Berakhir' ?></span></td>
                    <?php if ($canManage): ?>
                    <td>
                        <a href="<?= site_url("kerjasama/{$k['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= site_url("kerjasama/{$k['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus kerjasama ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                    <?php endif ?>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
