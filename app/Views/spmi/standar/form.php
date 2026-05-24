<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-rulers"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Standar Mutu</h4>
    <a href="<?= site_url('spmi/standar') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $isEdit ? site_url("spmi/standar/{$item['id']}/update") : site_url('spmi/standar/baru') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <?php foreach ($kategori as $k => $v): ?><option value="<?= $k ?>" <?= old('kategori', $item['kategori'] ?? 'sndikti') === $k ? 'selected' : '' ?>><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kode</label>
                <input type="text" name="kode" class="form-control" placeholder="mis. STD-PMB-01" value="<?= esc(old('kode', $item['kode'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Standar <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="mis. Standar Kompetensi Lulusan" value="<?= esc(old('nama', $item['nama'] ?? '')) ?>" required>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Pernyataan Standar</label>
                <textarea name="pernyataan" class="form-control" rows="2" placeholder="Pernyataan standar (audience-behaviour-competence-degree)..."><?= esc(old('pernyataan', $item['pernyataan'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Indikator</label>
                <textarea name="indikator" class="form-control" rows="2"><?= esc(old('indikator', $item['indikator'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Target</label>
                <input type="text" name="target" class="form-control" placeholder="mis. >=90%" value="<?= esc(old('target', $item['target'] ?? '')) ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Capaian</label>
                <input type="text" name="capaian" class="form-control" placeholder="mis. 87%" value="<?= esc(old('capaian', $item['capaian'] ?? '')) ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <?php foreach ($status as $k => $v): ?><option value="<?= $k ?>" <?= old('status', $item['status'] ?? 'belum') === $k ? 'selected' : '' ?>><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Bukti (PDF)</label>
                <?php $bk = $item['link_bukti'] ?? ''; ?>
                <?php if (!empty($bk)): ?>
                <div class="mb-1 small">
                    <?php if (\App\Services\EvidenceStorage::isStoredFile($bk)): ?>
                        <a href="<?= site_url('eviden/file/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti</a>
                    <?php else: ?>
                        <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Bukti</a>
                    <?php endif ?>
                    <label class="ms-2 text-danger"><input type="checkbox" name="hapus_link_bukti" value="1"> hapus</label>
                </div>
                <?php endif ?>
                <input type="file" name="link_bukti" class="form-control" accept="application/pdf">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('spmi/standar') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
