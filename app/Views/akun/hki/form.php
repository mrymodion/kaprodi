<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-shield-check"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> HKI Saya</h4>
    <a href="<?= site_url('akun/hki') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>
<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Judul / Nama Karya <span class="text-danger">*</span></label>
                <input type="text" name="judul" class="form-control" value="<?= esc(old('judul', $item['judul'] ?? '')) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Jenis HKI</label>
                <select name="jenis" class="form-select">
                    <?php foreach ($jenis as $k => $v): ?>
                        <option value="<?= $k ?>" <?= old('jenis', $item['jenis'] ?? '') === $k ? 'selected' : '' ?>><?= esc($v) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="penelitian" <?= old('kategori', $item['kategori'] ?? 'penelitian') === 'penelitian' ? 'selected' : '' ?>>Penelitian</option>
                    <option value="pkm" <?= old('kategori', $item['kategori'] ?? '') === 'pkm' ? 'selected' : '' ?>>PkM</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="granted" <?= old('status', $item['status'] ?? 'granted') === 'granted' ? 'selected' : '' ?>>Granted</option>
                    <option value="terdaftar" <?= old('status', $item['status'] ?? '') === 'terdaftar' ? 'selected' : '' ?>>Terdaftar</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">No. HKI / Sertifikat</label>
                <input type="text" name="no_hki" class="form-control" value="<?= esc(old('no_hki', $item['no_hki'] ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control" min="1990" max="2099" value="<?= esc(old('tahun', $item['tahun'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Pemilik / Inventor</label>
                <input type="text" name="pemilik" class="form-control" placeholder="Kosongkan untuk memakai nama Anda" value="<?= esc(old('pemilik', $item['pemilik'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Bukti (PDF) — sertifikat HKI</label>
                <?php $bk = $item['link_bukti'] ?? ''; ?>
                <?php if (!empty($bk)): ?>
                <div class="mb-1 small">
                    <?php if (\App\Services\EvidenceStorage::isStoredFile($bk)): ?>
                        <a href="<?= site_url('eviden/file/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti</a>
                    <?php else: ?>
                        <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Bukti (link lama)</a>
                    <?php endif ?>
                    <label class="ms-2 text-danger"><input type="checkbox" name="hapus_link_bukti" value="1"> hapus</label>
                </div>
                <?php endif ?>
                <input type="file" name="link_bukti" class="form-control" accept="application/pdf">
                <small class="text-muted">Unggah PDF (maks <?= (int) env('storage.maxUploadMb', 20) ?> MB).</small>
                <?= view('partials/hki_scan', ['scanUrl' => site_url('akun/hki/scan')]) ?>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('akun/hki') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
