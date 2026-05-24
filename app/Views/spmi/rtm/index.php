<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> Tinjauan Manajemen (RTM)</h4>
    <a href="<?= site_url('spmi/rtm/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah RTM</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Rapat Tinjauan Manajemen menutup siklus PPEPP: mencatat keputusan atas hasil evaluasi/audit dan rencana peningkatan.</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Tanggal</th><th>Agenda</th><th>Keputusan</th><th>Penanggung Jawab</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada catatan tinjauan manajemen.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <?php $b = ['terbuka' => 'bg-danger', 'proses' => 'bg-warning text-dark', 'selesai' => 'bg-success'][$r['status']] ?? 'bg-secondary'; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><small><?= $r['tanggal'] ? esc(date('d/m/Y', strtotime($r['tanggal']))) : '-' ?></small></td>
                        <td><?= esc($r['agenda']) ?></td>
                        <td><small class="text-muted"><?= esc(mb_strimwidth((string) $r['keputusan'], 0, 70, '…')) ?></small></td>
                        <td><small><?= esc($r['penanggung_jawab'] ?? '-') ?></small></td>
                        <td><span class="badge <?= $b ?>"><?= esc(ucfirst($r['status'])) ?></span></td>
                        <td class="text-end text-nowrap">
                            <?php if (!empty($r['link_bukti'])): ?>
                                <a href="<?= \App\Services\EvidenceStorage::isStoredFile($r['link_bukti']) ? site_url('eviden/file/' . $r['link_bukti']) : esc($r['link_bukti']) ?>" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1"><i class="bi bi-paperclip"></i></a>
                            <?php endif ?>
                            <a href="<?= site_url("spmi/rtm/{$r['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("spmi/rtm/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
