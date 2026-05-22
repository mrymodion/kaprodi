<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-building"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Prodi</h4>
    <a href="<?= site_url('prodi') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
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
                    <label class="form-label fw-semibold">Nama Prodi <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" required value="<?= esc(old('nama', $item['nama'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kode PDDIKTI <span class="text-danger">*</span></label>
                    <input type="text" name="kode_pddikti" class="form-control" required value="<?= esc(old('kode_pddikti', $item['kode_pddikti'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenjang <span class="text-danger">*</span></label>
                    <select name="jenjang" class="form-select">
                        <?php foreach (['D1','D2','D3','D4','S1','S2','S3','Profesi','Sp1','Sp2'] as $j): ?>
                        <option value="<?= $j ?>" <?= old('jenjang', $item['jenjang'] ?? 'D3') === $j ? 'selected' : '' ?>><?= $j ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Unit Pengelola (UPPS) <span class="text-danger">*</span></label>
                    <select name="upps_id" class="form-select" required>
                        <option value="">— Pilih UPPS —</option>
                        <?php foreach ($uppsList as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= (string)old('upps_id', $item['upps_id'] ?? '') === (string)$u['id'] ? 'selected' : '' ?>>
                            <?= esc($u['upps_nama'] ?: '-') ?><?= $u['pt_nama'] ? ' — ' . esc($u['pt_nama']) : '' ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Gelar Lulusan</label>
                    <input type="text" name="gelar" class="form-control" placeholder="mis. Ahli Madya Komputer" value="<?= esc(old('gelar', $item['gelar'] ?? '')) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Singkatan Gelar</label>
                    <input type="text" name="singkatan_gelar" class="form-control" placeholder="mis. A.Md.Kom" value="<?= esc(old('singkatan_gelar', $item['singkatan_gelar'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Peringkat Akreditasi</label>
                    <input type="text" name="akreditasi_saat_ini" class="form-control" placeholder="mis. Baik Sekali" value="<?= esc(old('akreditasi_saat_ini', $item['akreditasi_saat_ini'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. SK Akreditasi</label>
                    <input type="text" name="no_sk_akreditasi" class="form-control" value="<?= esc(old('no_sk_akreditasi', $item['no_sk_akreditasi'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php foreach (['aktif'=>'Aktif','non_aktif'=>'Non-aktif'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('status', $item['status'] ?? 'aktif') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tgl SK Akreditasi</label>
                    <input type="date" name="tgl_sk_akreditasi" class="form-control" value="<?= esc(old('tgl_sk_akreditasi', $item['tgl_sk_akreditasi'] ?? '')) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tgl Kadaluarsa Akreditasi</label>
                    <input type="date" name="tgl_kadaluarsa" class="form-control" value="<?= esc(old('tgl_kadaluarsa', $item['tgl_kadaluarsa'] ?? '')) ?>">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= site_url('prodi') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
