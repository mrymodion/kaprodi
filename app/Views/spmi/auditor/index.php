<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-vcard"></i> Registry Auditor SPMI</h4>
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
    <div class="col-6 col-md-3"><div class="card border-primary text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-primary"><?= $ringkas['total'] ?></div><div class="small text-muted">Total Auditor</div></div></div></div>
    <div class="col-6 col-md-3"><div class="card border-success text-center"><div class="card-body p-2"><div class="fs-3 fw-bold text-success"><?= $ringkas['tersertifikasi'] ?></div><div class="small text-muted">Bersertifikat</div></div></div></div>
</div>

<div class="card mb-3">
    <div class="card-header bg-success text-white fw-semibold">Tambah Auditor</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('spmi/auditor/baru') ?>" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label class="form-label small mb-0">Dosen (internal)</label>
                <select name="dosen_id" class="form-select form-select-sm">
                    <option value="">— pilih dosen —</option>
                    <?php foreach ($dosenList as $d): ?>
                        <?php $nm = trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '')); ?>
                        <option value="<?= (int) $d['id'] ?>"><?= esc($nm) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">atau Nama (eksternal)</label>
                <input type="text" name="nama" class="form-control form-control-sm" placeholder="kosongkan jika pilih dosen">
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">Lembaga Sertifikasi</label>
                <input type="text" name="lembaga" class="form-control form-control-sm">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">No. Sertifikat</label>
                <input type="text" name="no_sertifikat" class="form-control form-control-sm">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tersertifikasi" value="1" id="cs">
                    <label class="form-check-label small" for="cs">Auditor bersertifikat</label>
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Nama</th><th>Tipe</th><th>Sertifikasi</th><th>No. Sertifikat</th><th>Lembaga</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada auditor.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc(!empty($r['dosen_nama']) ? trim($r['dosen_nama']) : $r['nama']) ?></td>
                        <td><?= !empty($r['dosen_id']) ? '<span class="badge bg-info">Internal</span>' : '<span class="badge bg-secondary">Eksternal</span>' ?></td>
                        <td><?= $r['tersertifikasi'] ? '<i class="bi bi-patch-check-fill text-success"></i> Ya' : '<span class="text-muted">Belum</span>' ?></td>
                        <td><small><?= esc($r['no_sertifikat'] ?? '-') ?></small></td>
                        <td><small><?= esc($r['lembaga'] ?? '-') ?></small></td>
                        <td class="text-end">
                            <form method="post" action="<?= site_url("spmi/auditor/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus auditor ini?')">
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
