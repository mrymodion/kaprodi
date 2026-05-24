<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Temuan Audit</h4>
    <a href="<?= site_url('spmi/ami') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $isEdit ? site_url("spmi/ami/{$item['id']}/update") : site_url('spmi/ami/baru') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal Audit</label>
                <input type="date" name="tanggal_audit" class="form-control" value="<?= esc(old('tanggal_audit', $item['tanggal_audit'] ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="form-select">
                    <option value="">—</option>
                    <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= (int) old('tahun_akademik_id', $item['tahun_akademik_id'] ?? 0) === (int) $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <?php foreach ($kategori as $k => $v): ?><option value="<?= $k ?>" <?= old('kategori', $item['kategori'] ?? 'minor') === $k ? 'selected' : '' ?>><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <?php foreach ($status as $k => $v): ?><option value="<?= $k ?>" <?= old('status', $item['status'] ?? 'terbuka') === $k ? 'selected' : '' ?>><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label fw-semibold">Lingkup / Area Audit <span class="text-danger">*</span></label>
                <input type="text" name="lingkup" class="form-control" placeholder="mis. Proses pembelajaran, SDM, Tata Pamong" value="<?= esc(old('lingkup', $item['lingkup'] ?? '')) ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kriteria Terkait</label>
                <input type="text" name="kriteria" class="form-control" placeholder="mis. C3 / 3.2.A" value="<?= esc(old('kriteria', $item['kriteria'] ?? '')) ?>">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi Temuan <span class="text-danger">*</span></label>
                <textarea name="deskripsi" class="form-control" rows="3" required><?= esc(old('deskripsi', $item['deskripsi'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Akar Masalah</label>
                <textarea name="akar_masalah" class="form-control" rows="2"><?= esc(old('akar_masalah', $item['akar_masalah'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Rekomendasi</label>
                <textarea name="rekomendasi" class="form-control" rows="2"><?= esc(old('rekomendasi', $item['rekomendasi'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" class="form-control" rows="2"><?= esc(old('tindak_lanjut', $item['tindak_lanjut'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Auditor</label>
                <input type="text" name="auditor" class="form-control" value="<?= esc(old('auditor', $item['auditor'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
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
            <a href="<?= site_url('spmi/ami') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
