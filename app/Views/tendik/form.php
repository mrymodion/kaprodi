<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-workspace"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Tenaga Kependidikan</h4>
    <a href="<?= site_url('tendik') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-7">
                <label class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                <input type="text" name="nama" class="form-control" required value="<?= esc(old('nama', $item['nama'] ?? '')) ?>">
            </div>
            <div class="col-md-5">
                <label class="form-label fw-semibold">NIP</label>
                <input type="text" name="nip" class="form-control" value="<?= esc(old('nip', $item['nip'] ?? '')) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                <select name="jenis" class="form-select">
                    <?php foreach ($jenis as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('jenis', $item['jenis'] ?? 'administrasi')===$v?'selected':'' ?>><?= esc($l) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                <select name="pendidikan_terakhir" class="form-select">
                    <option value="">—</option>
                    <?php foreach (['S3','S2','S1','D4','D3','D2','D1','SMA'] as $p): ?>
                        <option value="<?= $p ?>" <?= old('pendidikan_terakhir', $item['pendidikan_terakhir'] ?? '')===$p?'selected':'' ?>><?= $p ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="aktif" value="1" id="aktif" <?= old('aktif', $item['aktif'] ?? 1) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="aktif">Aktif</label>
                </div>
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Sertifikat Kompetensi</label>
                <input type="text" name="sertifikat_kompetensi" class="form-control" value="<?= esc(old('sertifikat_kompetensi', $item['sertifikat_kompetensi'] ?? '')) ?>" placeholder="mis. Sertifikat Pustakawan / Teknisi Lab">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('tendik') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?php if ($isEdit): ?>
    <?= view('dokumen/_panel', ['ref' => 'tendik', 'ownerId' => $item['id'], 'dokumen' => $dokumen ?? [], 'jenisOpt' => $jenisOpt ?? []]) ?>
<?php endif ?>

<?= $this->endSection() ?>
