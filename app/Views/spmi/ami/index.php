<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Audit Mutu Internal (Temuan)</h4>
    <a href="<?= site_url('spmi/ami/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Temuan</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="card border-primary text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-primary"><?= $ringkas['total'] ?></div><div class="small text-muted">Total Temuan</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-danger text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-danger"><?= $ringkas['terbuka'] ?></div><div class="small text-muted">Terbuka</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-warning text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-warning"><?= $ringkas['proses'] ?></div><div class="small text-muted">Proses</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-success text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-success"><?= $ringkas['selesai'] ?></div><div class="small text-muted">Selesai</div></div></div></div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Tanggal</th><th>Lingkup</th><th>Kriteria</th><th>Kategori</th><th>Deskripsi</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada temuan audit.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <?php $badge = ['terbuka' => 'bg-danger', 'proses' => 'bg-warning text-dark', 'selesai' => 'bg-success'][$r['status']] ?? 'bg-secondary'; ?>
                    <?php $kbadge = ['mayor' => 'bg-danger', 'minor' => 'bg-warning text-dark', 'observasi' => 'bg-info'][$r['kategori']] ?? 'bg-secondary'; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><small><?= $r['tanggal_audit'] ? esc(date('d/m/y', strtotime($r['tanggal_audit']))) : '-' ?></small></td>
                        <td><?= esc($r['lingkup']) ?></td>
                        <td><small><?= esc($r['kriteria'] ?? '-') ?></small></td>
                        <td><span class="badge <?= $kbadge ?>"><?= esc(ucfirst($r['kategori'])) ?></span></td>
                        <td><small><?= esc(mb_strimwidth((string) $r['deskripsi'], 0, 80, '…')) ?></small></td>
                        <td><span class="badge <?= $badge ?>"><?= esc(ucfirst($r['status'])) ?></span></td>
                        <td class="text-end text-nowrap">
                            <?php if (!empty($r['link_bukti'])): ?>
                                <a href="<?= \App\Services\EvidenceStorage::isStoredFile($r['link_bukti']) ? site_url('eviden/file/' . $r['link_bukti']) : esc($r['link_bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1"><i class="bi bi-paperclip"></i></a>
                            <?php endif ?>
                            <a href="<?= site_url("spmi/ami/{$r['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("spmi/ami/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus temuan ini?')">
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
