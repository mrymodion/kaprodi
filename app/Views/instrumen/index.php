<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4 class="mb-3"><i class="bi bi-sliders"></i> Instrumen LAM</h4>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<!-- Daftar instrumen + struktur -->
<div class="card mb-4">
    <div class="card-header bg-light fw-semibold"><i class="bi bi-list-check"></i> Daftar Instrumen Terpasang</div>
    <div class="card-body">
        <?php if (empty($instrumen)): ?>
            <p class="text-muted mb-0">Belum ada instrumen. Jalankan seeder <code>LamInfokomD3Seeder</code> terlebih dahulu.</p>
        <?php else: ?>
        <?php foreach ($instrumen as $ins): ?>
        <div class="border rounded p-3 mb-3">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1"><?= esc($ins['nama']) ?>
                        <?php if ($ins['aktif']): ?><span class="badge bg-success">Aktif</span><?php else: ?><span class="badge bg-secondary">Non-aktif</span><?php endif ?>
                    </h6>
                    <small class="text-muted">
                        <?= esc($ins['lembaga_nama'] ?: '-') ?> &middot; Jenjang <?= esc($ins['jenjang']) ?> &middot; Versi <?= esc($ins['versi']) ?>
                    </small>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary"><?= $ins['jml_kriteria'] ?> kriteria</span>
                    <span class="badge bg-info text-dark"><?= $ins['jml_butir'] ?> butir</span>
                    <span class="badge bg-warning text-dark"><?= $ins['jml_tabel'] ?> tabel LKPS</span>
                    <span class="badge bg-secondary"><?= $ins['jml_template'] ?> template LED</span>
                </div>
            </div>
            <?php if (!empty($ins['kriteria'])): ?>
            <div class="table-responsive mt-2">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light"><tr><th style="width:80px">Kode</th><th>Kriteria</th><th style="width:90px" class="text-end">Bobot</th></tr></thead>
                    <tbody>
                    <?php foreach ($ins['kriteria'] as $k): ?>
                        <tr><td><?= esc($k['kode']) ?></td><td><?= esc($k['nama']) ?></td><td class="text-end"><?= esc($k['bobot']) ?></td></tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php endif ?>
        </div>
        <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<!-- Pemetaan prodi -> instrumen -->
<div class="card">
    <div class="card-header bg-light fw-semibold"><i class="bi bi-diagram-3"></i> Pemetaan Prodi → Instrumen Aktif</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>Prodi</th><th>Jenjang</th><th style="width:55%">Instrumen Aktif</th></tr></thead>
                <tbody>
                <?php if (empty($prodi)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">Belum ada prodi.</td></tr>
                <?php else: ?>
                <?php foreach ($prodi as $p): ?>
                <tr>
                    <td><?= esc($p['nama']) ?></td>
                    <td><span class="badge bg-secondary"><?= esc($p['jenjang']) ?></span></td>
                    <td>
                        <form method="post" action="<?= site_url('instrumen/assign') ?>" class="d-flex gap-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="prodi_id" value="<?= $p['id'] ?>">
                            <select name="lam_instrumen_id" class="form-select form-select-sm">
                                <option value="0">— Tidak ada / non-aktifkan —</option>
                                <?php foreach ($instrumen as $ins): ?>
                                <option value="<?= $ins['id'] ?>" <?= (int)$p['aktif_instrumen_id'] === (int)$ins['id'] ? 'selected' : '' ?>>
                                    <?= esc($ins['nama']) ?> (<?= esc($ins['jenjang']) ?>)
                                </option>
                                <?php endforeach ?>
                            </select>
                            <button class="btn btn-sm btn-primary text-nowrap"><i class="bi bi-save"></i> Simpan</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
