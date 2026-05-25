<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-shield-check"></i> HKI Saya</h4>
    <a href="<?= site_url('akun/hki/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah HKI</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Catat luaran HKI Anda (paten, hak cipta, dll) untuk LKPS 3.C.3 / 4.C.3. Unggah sertifikat (PDF) sebagai bukti.</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Jenis</th><th>No. HKI</th><th>Tahun</th><th>Status</th><th>Bukti</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada HKI.</td></tr>
                <?php else: foreach ($list as $i => $h): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($h['judul']) ?></td>
                        <td><small><?= esc($jenis[$h['jenis']] ?? $h['jenis']) ?></small></td>
                        <td><?= esc($h['no_hki'] ?? '-') ?></td>
                        <td><?= esc($h['tahun'] ?? '-') ?></td>
                        <td><span class="badge <?= $h['status'] === 'granted' ? 'bg-success' : 'bg-warning text-dark' ?>"><?= esc($h['status']) ?></span></td>
                        <td>
                            <?php $bk = $h['link_bukti'] ?? ''; ?>
                            <?php if (\App\Services\EvidenceStorage::isStoredFile($bk)): ?>
                                <a href="<?= site_url('eviden/file/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i></a>
                            <?php elseif (!empty($bk)): ?>
                                <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i></a>
                            <?php else: ?><span class="text-muted">-</span><?php endif ?>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url("akun/hki/{$h['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("akun/hki/{$h['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus HKI ini?')">
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

<?php if (!empty($claimable)): ?>
<div class="card mt-4 border-warning">
    <div class="card-header bg-warning-subtle fw-semibold"><i class="bi bi-inbox"></i> HKI Prodi Belum Bertuan (<?= count($claimable) ?>)</div>
    <div class="card-body">
        <p class="small text-muted mb-3">HKI berikut ada di data prodi tetapi belum ditautkan ke dosen. Klik <strong>Klaim</strong> bila ini milik Anda.</p>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Judul</th><th>Jenis</th><th>Tahun</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($claimable as $i => $h): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($h['judul']) ?></td>
                        <td><small><?= esc($jenis[$h['jenis']] ?? $h['jenis']) ?></small></td>
                        <td><?= esc($h['tahun'] ?? '-') ?></td>
                        <td class="text-end">
                            <form method="post" action="<?= site_url("akun/hki/{$h['id']}/klaim") ?>" class="d-inline" onsubmit="return confirm('Klaim HKI ini sebagai milik Anda?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-success py-0 px-2"><i class="bi bi-hand-index"></i> Klaim</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif ?>

<?= $this->endSection() ?>
