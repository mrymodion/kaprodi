<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-building-gear"></i> Sarana & Prasarana</h4>
    <a href="<?= site_url('sarpras/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr>
                    <th>#</th><th>Nama</th><th>Kategori</th><th class="text-center">Jumlah</th>
                    <th class="text-center">Luas (m²)</th><th class="text-center">Kapasitas</th><th>Kondisi</th>
                    <th class="text-center">Pddk</th><th class="text-center">Pnltn</th><th class="text-center">PkM</th><th></th>
                </tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="11" class="text-center text-muted py-4">Belum ada data sarana/prasarana.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $s): $ck = fn($v)=> $v ? '<i class="bi bi-check-lg text-success"></i>' : '<span class="text-muted">–</span>'; ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= esc($s['nama']) ?><?php if ($s['keterangan']): ?><br><small class="text-muted"><?= esc($s['keterangan']) ?></small><?php endif ?></td>
                        <td><span class="badge bg-secondary"><?= esc($kategori[$s['kategori']] ?? $s['kategori']) ?></span></td>
                        <td class="text-center"><?= (int)$s['jumlah'] ?></td>
                        <td class="text-center"><?= $s['luas_m2'] !== null ? esc($s['luas_m2']) : '-' ?></td>
                        <td class="text-center"><?= $s['kapasitas'] !== null ? (int)$s['kapasitas'] : '-' ?></td>
                        <td><?= esc(str_replace('_',' ', ucfirst($s['kondisi']))) ?></td>
                        <td class="text-center"><?= $ck($s['untuk_pendidikan']) ?></td>
                        <td class="text-center"><?= $ck($s['untuk_penelitian']) ?></td>
                        <td class="text-center"><?= $ck($s['untuk_pkm']) ?></td>
                        <td>
                            <a href="<?= site_url("sarpras/{$s['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("sarpras/{$s['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
    <div class="card-footer small text-muted">Kolom Pddk/Pnltn/PkM menandai pemakaian sarana untuk Pendidikan, Penelitian, dan PkM (mengisi LKPS 5.2 / 3.A.1 / 4.A.1).</div>
</div>

<?= $this->endSection() ?>
