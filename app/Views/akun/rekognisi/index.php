<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-award"></i> Rekognisi Saya</h4>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Catat pengakuan/rekognisi atas kepakaran Anda: reviewer/editor jurnal, keynote speaker, asesor, narasumber, penghargaan, dll (mendukung kriteria C3 SDM).</div>

<div class="card mb-3">
    <div class="card-header bg-success text-white fw-semibold">Tambah Rekognisi</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('akun/rekognisi/baru') ?>" enctype="multipart/form-data" class="row g-2">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label class="form-label small mb-0">Jenis <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select form-select-sm">
                    <?php foreach ($jenis as $k => $v): ?><option value="<?= $k ?>"><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-0">Judul / Nama Kegiatan <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control form-control-sm" placeholder="mis. Reviewer Jurnal X / Keynote Konferensi Y" required>
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">Tingkat</label>
                <select name="tingkat" class="form-select form-select-sm">
                    <option value="lokal">Lokal</option>
                    <option value="nasional" selected>Nasional</option>
                    <option value="internasional">Internasional</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-0">Penyelenggara / Institusi</label>
                <input type="text" name="penyelenggara" class="form-control form-control-sm">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Tahun</label>
                <input type="number" name="tahun" class="form-control form-control-sm" min="1990" max="2099">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-0">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="form-select form-select-sm">
                    <option value="">—</option>
                    <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>"><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
            <div class="col-12">
                <label class="form-label small mb-0">Bukti (PDF, opsional)</label>
                <input type="file" name="link_bukti" class="form-control form-control-sm" accept="application/pdf">
                <?= view('partials/doc_scan', ['scanUrl' => site_url('akun/rekognisi/scan')]) ?>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Jenis</th><th>Judul</th><th>Penyelenggara</th><th>Tingkat</th><th>Tahun</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data rekognisi.</td></tr>
                <?php else: foreach ($list as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
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
                            <form method="post" action="<?= site_url("akun/rekognisi/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus rekognisi ini?')">
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
