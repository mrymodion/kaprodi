<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-folder-check"></i> Dokumen Mutu SPMI</h4>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Kelola dokumen mutu: Kebijakan SPMI, Manual Mutu, Standar, SOP/Prosedur, dan Formulir.</div>

<div class="card mb-3">
    <div class="card-header bg-success text-white fw-semibold">Tambah Dokumen Mutu</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('spmi/dokumen/baru') ?>" enctype="multipart/form-data" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-md-3">
                <label class="form-label small mb-0">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <?php foreach ($jenisOpt as $k => $v): ?><option value="<?= $k ?>"><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-0">Judul Dokumen <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Kode</label>
                <input type="text" name="kode" class="form-control form-control-sm" placeholder="mis. STD-01">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Versi</label>
                <input type="text" name="versi" class="form-control form-control-sm" placeholder="mis. 1.0">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">Tanggal Berlaku</label>
                <input type="date" name="tanggal_berlaku" class="form-control form-control-sm">
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-0">Berkas (PDF)</label>
                <input type="file" name="link_bukti" class="form-control form-control-sm" accept="application/pdf">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Jenis</th><th>Kode</th><th>Judul</th><th>Versi</th><th>Berlaku</th><th>Berkas</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada dokumen mutu.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><span class="badge bg-light text-dark border"><?= esc($jenisOpt[$r['jenis']] ?? $r['jenis']) ?></span></td>
                        <td><small><?= esc($r['kode'] ?? '-') ?></small></td>
                        <td><?= esc($r['judul']) ?></td>
                        <td><small><?= esc($r['versi'] ?? '-') ?></small></td>
                        <td><small><?= $r['tanggal_berlaku'] ? esc(date('d/m/Y', strtotime($r['tanggal_berlaku']))) : '-' ?></small></td>
                        <td><?php if (!empty($r['link_bukti'])): ?>
                            <a href="<?= \App\Services\EvidenceStorage::isStoredFile($r['link_bukti']) ? site_url('eviden/file/' . $r['link_bukti']) : esc($r['link_bukti']) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i></a>
                        <?php else: ?><span class="text-muted">-</span><?php endif ?></td>
                        <td class="text-end">
                            <form method="post" action="<?= site_url("spmi/dokumen/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
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
