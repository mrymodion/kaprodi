<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-people-fill"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Tinjauan Manajemen</h4>
    <a href="<?= site_url('spmi/rtm') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $isEdit ? site_url("spmi/rtm/{$item['id']}/update") : site_url('spmi/rtm/baru') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= esc(old('tanggal', $item['tanggal'] ?? '')) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Agenda <span class="text-danger">*</span></label>
                <input type="text" name="agenda" class="form-control" placeholder="mis. Tinjauan hasil AMI & kepuasan" value="<?= esc(old('agenda', $item['agenda'] ?? '')) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <?php foreach ($status as $k => $v): ?><option value="<?= $k ?>" <?= old('status', $item['status'] ?? 'terbuka') === $k ? 'selected' : '' ?>><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Keputusan / Hasil Tinjauan</label>
                <textarea name="keputusan" class="form-control" rows="4"><?= esc(old('keputusan', $item['keputusan'] ?? '')) ?></textarea>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">Penanggung Jawab</label>
                <input type="text" name="penanggung_jawab" class="form-control" value="<?= esc(old('penanggung_jawab', $item['penanggung_jawab'] ?? '')) ?>">
            </div>
            <div class="col-md-7">
                <label class="form-label fw-semibold">Tindak Lanjut</label>
                <textarea name="tindak_lanjut" class="form-control" rows="2"><?= esc(old('tindak_lanjut', $item['tindak_lanjut'] ?? '')) ?></textarea>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Bukti (PDF) — notulen/SK</label>
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
            <a href="<?= site_url('spmi/rtm') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
