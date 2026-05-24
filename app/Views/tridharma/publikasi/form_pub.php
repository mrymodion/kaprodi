<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Publikasi</h4>
    <a href="<?= $backUrl ?? site_url('publikasi') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
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
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="kategori" class="form-select" required>
                        <option value="penelitian" <?= old('kategori', $item['kategori'] ?? 'penelitian') === 'penelitian' ? 'selected' : '' ?>>Penelitian</option>
                        <option value="pkm" <?= old('kategori', $item['kategori'] ?? '') === 'pkm' ? 'selected' : '' ?>>PkM</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Jenis Publikasi <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-select" required>
                        <?php foreach ($jenis as $v => $l): ?>
                        <option value="<?= $v ?>" <?= old('jenis', $item['jenis'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
                    <textarea name="judul" class="form-control" rows="2" required><?= esc(old('judul', $item['judul'] ?? '')) ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Penulis</label>
                    <input type="text" name="penulis" class="form-control" placeholder="Pisahkan dengan koma" value="<?= esc(old('penulis', $item['penulis'] ?? '')) ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Jurnal/Prosiding/Media</label>
                    <input type="text" name="nama_media" class="form-control" value="<?= esc(old('nama_media', $item['nama_media'] ?? '')) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tahun</label>
                    <input type="number" name="tahun" class="form-control" min="2000" max="2099" value="<?= esc(old('tahun', $item['tahun'] ?? date('Y'))) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Volume/No</label>
                    <input type="text" name="volume" class="form-control" value="<?= esc(old('volume', $item['volume'] ?? '')) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sitasi</label>
                    <input type="number" name="sitasi" class="form-control" min="0" value="<?= esc(old('sitasi', $item['sitasi'] ?? '0')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">DOI</label>
                    <input type="text" name="doi" class="form-control" placeholder="10.xxxx/..." value="<?= esc(old('doi', $item['doi'] ?? '')) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">URL</label>
                    <input type="url" name="url" class="form-control" placeholder="https://..." value="<?= esc(old('url', $item['url'] ?? '')) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Skor SINTA</label>
                    <select name="sinta_score" class="form-select">
                        <option value="">-</option>
                        <?php for ($s=1; $s<=6; $s++): ?>
                        <option value="<?= $s ?>" <?= old('sinta_score', $item['sinta_score'] ?? '') == $s ? 'selected' : '' ?>>S<?= $s ?></option>
                        <?php endfor ?>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= $backUrl ?? site_url('publikasi') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
