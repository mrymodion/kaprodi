<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-link-45deg"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Kerjasama</h4>
    <a href="<?= site_url('kerjasama') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0">
    <?php foreach ((array)session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
</ul></div>
<?php endif ?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>">
            <?= csrf_field() ?>
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Nama Mitra <span class="text-danger">*</span></label>
                    <input type="text" name="nama_mitra" class="form-control" required value="<?= esc(old('nama_mitra', $item['nama_mitra'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Dokumen</label>
                    <select name="jenis_dokumen" class="form-select">
                        <?php foreach (['mou'=>'MoU','moa'=>'MoA','ia'=>'IA (Implementasi)','sk'=>'SK'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('jenis_dokumen', $item['jenis_dokumen'] ?? 'mou') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Bidang Kerjasama</label>
                    <select name="bidang" class="form-select">
                        <?php foreach (['pendidikan'=>'Pendidikan','penelitian'=>'Penelitian','pkm'=>'PkM','tridharma'=>'Tridharma'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('bidang', $item['bidang'] ?? 'pendidikan') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tingkat</label>
                    <select name="tingkat" class="form-select">
                        <?php foreach (['lokal'=>'Lokal','wilayah'=>'Wilayah','nasional'=>'Nasional','internasional'=>'Internasional'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('tingkat', $item['tingkat'] ?? 'nasional') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. Dokumen</label>
                    <input type="text" name="no_dokumen" class="form-control" value="<?= esc(old('no_dokumen', $item['no_dokumen'] ?? '')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Mulai <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_mulai" class="form-control" required value="<?= esc(old('tgl_mulai', $item['tgl_mulai'] ?? '')) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Tanggal Berakhir</label>
                    <input type="date" name="tgl_berakhir" class="form-control" value="<?= esc(old('tgl_berakhir', $item['tgl_berakhir'] ?? '')) ?>">
                    <small class="text-muted">Kosongkan jika tidak terbatas</small>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Manfaat Kerjasama</label>
                    <button type="button" class="btn btn-sm btn-outline-info mb-1 ms-2 btn-ai-borang"
                        data-jenis="manfaat_kerjasama" data-target="manfaat" data-ctx='{"mitra":"nama_mitra","bidang":"bidang"}'><i class="bi bi-robot"></i> Bantu AI</button>
                    <textarea name="manfaat" class="form-control" rows="2"><?= esc(old('manfaat', $item['manfaat'] ?? '')) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Bukti Implementasi</label>
                    <textarea name="bukti_implementasi" class="form-control" rows="2" placeholder="Kegiatan yang sudah dilaksanakan berdasarkan MoU ini"><?= esc(old('bukti_implementasi', $item['bukti_implementasi'] ?? '')) ?></textarea>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= site_url('kerjasama') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= view("partials/ai_borang_script") ?>

<?= $this->endSection() ?>
