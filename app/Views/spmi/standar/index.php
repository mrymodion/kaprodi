<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-rulers"></i> Register Standar Mutu</h4>
    <a href="<?= site_url('spmi/standar/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Standar</a>
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
    <div class="col-6 col-md-3"><div class="card border-primary text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-primary"><?= $ringkas['total'] ?></div><div class="small text-muted">Total Standar</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-success text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-success"><?= $ringkas['tercapai'] ?></div><div class="small text-muted">Tercapai</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-warning text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-warning"><?= $ringkas['sebagian'] ?></div><div class="small text-muted">Sebagian</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-info text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-info"><?= $ringkas['melampaui'] ?></div><div class="small text-muted">Melampaui SN-Dikti</div></div></div></div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Kategori</th><th>Kode</th><th>Standar</th><th>Target</th><th>Capaian</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada standar mutu.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <?php $b = ['tercapai' => 'bg-success', 'sebagian' => 'bg-warning text-dark', 'belum' => 'bg-danger'][$r['status']] ?? 'bg-secondary'; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge <?= $r['kategori'] === 'melampaui' ? 'bg-info' : 'bg-light text-dark border' ?>"><?= esc($kategori[$r['kategori']] ?? $r['kategori']) ?></span></td>
                        <td><small><?= esc($r['kode'] ?? '-') ?></small></td>
                        <td><?= esc($r['nama']) ?>
                            <?php if (!empty($r['indikator'])): ?><br><small class="text-muted"><?= esc(mb_strimwidth((string) $r['indikator'], 0, 80, '…')) ?></small><?php endif ?>
                        </td>
                        <td><small><?= esc($r['target'] ?? '-') ?></small></td>
                        <td><small><?= esc($r['capaian'] ?? '-') ?></small></td>
                        <td><span class="badge <?= $b ?>"><?= esc($status[$r['status']] ?? $r['status']) ?></span></td>
                        <td class="text-end text-nowrap">
                            <?php if (!empty($r['link_bukti'])): ?>
                                <a href="<?= \App\Services\EvidenceStorage::isStoredFile($r['link_bukti']) ? site_url('eviden/file/' . $r['link_bukti']) : esc($r['link_bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1"><i class="bi bi-paperclip"></i></a>
                            <?php endif ?>
                            <a href="<?= site_url("spmi/standar/{$r['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("spmi/standar/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus standar ini?')">
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
