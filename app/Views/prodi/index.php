<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-building"></i> Program Studi</h4>
    <a href="<?= site_url('prodi/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Prodi</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Kode PDDIKTI</th>
                        <th>Nama Prodi</th>
                        <th>Jenjang</th>
                        <th>Akreditasi</th>
                        <th>UPPS / PT</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data prodi.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $p): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= esc($p['kode_pddikti']) ?></td>
                    <td><?= esc($p['nama']) ?><?php if ($p['gelar']): ?><br><small class="text-muted"><?= esc($p['gelar']) ?></small><?php endif ?></td>
                    <td><span class="badge bg-secondary"><?= esc($p['jenjang']) ?></span></td>
                    <td><?= $p['akreditasi_saat_ini'] ? esc($p['akreditasi_saat_ini']) : '<span class="text-muted">—</span>' ?></td>
                    <td><small><?= esc($p['upps_nama'] ?: '—') ?><?php if ($p['pt_nama']): ?><br class="d-none d-md-block"><span class="text-muted"><?= esc($p['pt_nama']) ?></span><?php endif ?></small></td>
                    <td><span class="badge <?= $p['status'] === 'aktif' ? 'bg-success' : 'bg-secondary' ?>"><?= esc(ucfirst($p['status'])) ?></span></td>
                    <td>
                        <a href="<?= site_url("prodi/{$p['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= site_url("prodi/{$p['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus prodi ini? Data terkait (dosen, mahasiswa, dll) dapat ikut terhapus.')">
                            <?= csrf_field() ?>
                            <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
