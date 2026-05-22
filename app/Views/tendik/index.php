<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-workspace"></i> Tenaga Kependidikan</h4>
    <a href="<?= site_url('tendik/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
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
                    <th>#</th><th>Nama</th><th>NIP</th><th>Jenis</th><th>Pendidikan</th><th>Sertifikat</th><th>Status</th><th></th>
                </tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada data tenaga kependidikan.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $t): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= esc($t['nama']) ?></td>
                        <td><?= esc($t['nip'] ?? '-') ?></td>
                        <td><span class="badge bg-secondary"><?= esc($jenis[$t['jenis']] ?? $t['jenis']) ?></span></td>
                        <td><?= esc($t['pendidikan_terakhir'] ?? '-') ?></td>
                        <td><small><?= esc($t['sertifikat_kompetensi'] ?? '-') ?></small></td>
                        <td><span class="badge bg-<?= $t['aktif']?'success':'secondary' ?>"><?= $t['aktif']?'Aktif':'Non-aktif' ?></span></td>
                        <td>
                            <a href="<?= site_url("tendik/{$t['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("tendik/{$t['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
    <div class="card-footer small text-muted">Rekap kualifikasi (jenis × pendidikan) otomatis mengisi LKPS 1.A.5.</div>
</div>

<?= $this->endSection() ?>
