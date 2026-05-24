<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $base = site_url('akun/' . $tipe); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi <?= esc($icon) ?>"></i> <?= esc($judul) ?> Saya</h4>
    <a href="<?= $base ?>/baru" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Hanya kegiatan yang <strong>Anda ketuai</strong> yang tampil di sini. Bila Anda hanya anggota, kegiatan dikelola oleh ketua pelaksana.</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>#</th><th>Judul</th><th>TA</th><th>Tahun</th><th>Sumber Dana</th><th></th></tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: foreach ($list as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($r['judul']) ?></td>
                        <td><small><?= esc($r['ta_kode'] ?? '-') ?></small></td>
                        <td><?= esc($r['tahun_pelaksanaan'] ?? '-') ?></td>
                        <td><small><?= esc(ucwords(str_replace('_', ' ', $r['sumber_dana'] ?? '-'))) ?></small></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= $base ?>/<?= $r['id'] ?>/edit" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= $base ?>/<?= $r['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
