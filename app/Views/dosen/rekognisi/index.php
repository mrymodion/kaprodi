<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-award"></i> Rekognisi DTPR Prodi</h4>
    <div class="d-flex gap-2">
        <?php if (!empty($list)): ?><a href="<?= site_url('dosen/rekognisi/export') ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-excel"></i> Ekspor Excel</a><?php endif ?>
        <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-primary text-center"><div class="card-body p-2">
            <div class="fs-3 fw-bold text-primary"><?= $ringkas['total'] ?></div><div class="small text-muted">Total Rekognisi</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-success text-center"><div class="card-body p-2">
            <div class="fs-3 fw-bold text-success"><?= $ringkas['dosen'] ?></div><div class="small text-muted">Dosen Terlibat</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-warning text-center"><div class="card-body p-2">
            <div class="fs-3 fw-bold text-warning"><?= $ringkas['internasional'] ?></div><div class="small text-muted">Skala Internasional</div>
        </div></div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-info text-center"><div class="card-body p-2">
            <div class="fs-3 fw-bold text-info"><?= count($ringkas['per_jenis']) ?></div><div class="small text-muted">Ragam Jenis</div>
        </div></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-success text-white fw-semibold">Tambah Rekognisi (untuk DTPR)</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('dosen/rekognisi') ?>" enctype="multipart/form-data" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label class="form-label small mb-0">Dosen <span class="text-danger">*</span></label>
                <select name="dosen_id" class="form-select form-select-sm" required>
                    <option value="">— Pilih dosen —</option>
                    <?php foreach ($dosenList as $d): ?>
                        <?php $nm = trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '')); ?>
                        <option value="<?= (int) $d['id'] ?>"><?= esc($nm) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">Jenis</label>
                <select name="jenis" class="form-select form-select-sm">
                    <?php foreach ($jenis as $k => $v): ?><option value="<?= $k ?>"><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">Judul / Nama Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">Penyelenggara</label>
                <input type="text" name="penyelenggara" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Tingkat</label>
                <select name="tingkat" class="form-select form-select-sm">
                    <option value="lokal">Lokal</option><option value="nasional" selected>Nasional</option><option value="internasional">Internasional</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Tahun</label>
                <input type="number" name="tahun" class="form-control form-control-sm" min="1990" max="2099">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">TA</label>
                <select name="tahun_akademik_id" class="form-select form-select-sm">
                    <option value="">—</option>
                    <?php foreach ($taList as $ta): ?><option value="<?= $ta['id'] ?>"><?= esc($ta['kode']) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
            <div class="col-12">
                <label class="form-label small mb-0">Bukti (PDF, opsional)</label>
                <input type="file" name="link_bukti" class="form-control form-control-sm" accept="application/pdf">
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Dosen</th><th>Jenis</th><th>Judul</th><th>Penyelenggara</th><th>Tingkat</th><th>Tahun</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="8" class="text-center text-muted py-4">Belum ada rekognisi tercatat. Dosen dapat menambah sendiri lewat menu "Rekognisi Saya".</td></tr>
                <?php else: foreach ($list as $i => $r): ?>
                    <?php $nm = trim(($r['gelar_depan'] ? $r['gelar_depan'] . ' ' : '') . $r['dosen_nama'] . ($r['gelar_belakang'] ? ', ' . $r['gelar_belakang'] : '')); ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><small><?= esc($nm) ?></small></td>
                        <td><small><?= esc($jenis[$r['jenis']] ?? $r['jenis']) ?></small></td>
                        <td><?= esc($r['judul']) ?>
                            <?php $bk = $r['link_bukti'] ?? ''; ?>
                            <?php if (\App\Services\EvidenceStorage::isStoredFile($bk)): ?> <a href="<?= site_url('eviden/file/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i></a>
                            <?php elseif (!empty($bk)): ?> <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-link-45deg"></i></a><?php endif ?>
                        </td>
                        <td><small><?= esc($r['penyelenggara'] ?? '-') ?></small></td>
                        <td><span class="badge bg-light text-dark border"><?= esc(ucfirst($r['tingkat'])) ?></span></td>
                        <td><?= esc($r['tahun'] ?? '-') ?></td>
                        <td class="text-end">
                            <form method="post" action="<?= site_url("dosen/rekognisi/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus rekognisi ini?')">
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
