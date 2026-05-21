<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $canManage = auth()->user()->can('tridharma.manage'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-journal-text"></i> Penelitian</h4>
    <?php if ($canManage): ?>
    <a href="<?= site_url('penelitian/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Penelitian</a>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<!-- Summary Cards -->
<?php if ($summary): ?>
<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card border-primary text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-primary"><?= $summary['total'] ?></div>
                <div class="small text-muted">Total Penelitian</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-success"><?= number_format($summary['danaTotal']) ?></div>
                <div class="small text-muted">Total Dana (Rp)</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-info"><?= $summary['libatMhs'] ?></div>
                <div class="small text-muted">Libatkan Mahasiswa</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning text-center">
            <div class="card-body p-2">
                <?php
                $luarNegeri = 0;
                foreach ($summary['perSumber'] as $s) {
                    if ($s['sumber_dana'] === 'luar_negeri') $luarNegeri = (int)$s['jml'];
                }
                ?>
                <div class="fs-3 fw-bold text-warning"><?= $luarNegeri ?></div>
                <div class="small text-muted">Dana Luar Negeri</div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Filter -->
<form class="row g-2 mb-3" method="get">
    <div class="col-auto">
        <input type="number" name="tahun" class="form-control form-control-sm" placeholder="Tahun" value="<?= esc($filter['tahun'] ?? '') ?>" min="2000" max="2099" style="width:100px">
    </div>
    <div class="col-auto">
        <select name="sumber_dana" class="form-select form-select-sm" style="width:200px">
            <option value="">Semua Sumber Dana</option>
            <?php foreach (['pt_sendiri'=>'PT Sendiri','mandiri'=>'Mandiri','dalam_negeri'=>'Dalam Negeri','luar_negeri'=>'Luar Negeri'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= ($filter['sumber_dana']??'')===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        <a href="<?= site_url('penelitian') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<!-- Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Judul</th>
                        <th>Ketua</th>
                        <th>Sumber Dana</th>
                        <th class="text-end">Dana (Rp)</th>
                        <th>Tahun</th>
                        <th>Mhs Terlibat</th>
                        <?php if ($canManage): ?><th></th><?php endif ?>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data penelitian.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $p): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <?= esc($p['judul']) ?>
                        <?php if ($p['ta_kode']): ?><br><small class="text-muted"><?= esc($p['ta_kode']) ?></small><?php endif ?>
                    </td>
                    <td><?= esc($p['nama_ketua'] ?? '-') ?></td>
                    <td>
                        <?php $sumberLabels = ['pt_sendiri'=>'PT Sendiri','mandiri'=>'Mandiri','dalam_negeri'=>'Dalam Negeri','luar_negeri'=>'Luar Negeri']; ?>
                        <?= esc($sumberLabels[$p['sumber_dana']] ?? $p['sumber_dana']) ?>
                        <?php if ($p['nama_sumber_dana']): ?><br><small class="text-muted"><?= esc($p['nama_sumber_dana']) ?></small><?php endif ?>
                    </td>
                    <td class="text-end"><?= $p['jumlah_dana'] ? number_format($p['jumlah_dana']) : '-' ?></td>
                    <td><?= esc($p['tahun_pelaksanaan'] ?? '-') ?></td>
                    <td class="text-center">
                        <?php if ($p['libatkan_mahasiswa']): ?>
                            <span class="badge bg-success"><?= $p['jumlah_mhs_terlibat'] ?> mhs</span>
                        <?php else: ?>
                            <span class="text-muted">-</span>
                        <?php endif ?>
                    </td>
                    <?php if ($canManage): ?>
                    <td>
                        <a href="<?= site_url("penelitian/{$p['id']}/edit") ?>" class="btn btn-xs btn-outline-secondary btn-sm py-0 px-1"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= site_url("penelitian/{$p['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus penelitian ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-xs btn-outline-danger btn-sm py-0 px-1"><i class="bi bi-trash"></i></button>
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
