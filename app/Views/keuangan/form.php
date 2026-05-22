<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-cash-coin"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> Data Keuangan</h4>
    <a href="<?= site_url('keuangan') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card"><div class="card-body">
    <form method="post" action="<?= $action ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Arah <span class="text-danger">*</span></label>
                <select name="arah" class="form-select">
                    <?php foreach (['pemasukan'=>'Pemasukan (1.A.2)','pengeluaran'=>'Pengeluaran (1.A.3)'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('arah', $item['arah'] ?? 'pemasukan')===$v?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Sumber / Pos <span class="text-danger">*</span></label>
                <input type="text" name="sumber_atau_pos" class="form-control" required placeholder="mis. SPP Mahasiswa / Belanja Pendidikan" value="<?= esc(old('sumber_atau_pos', $item['sumber_atau_pos'] ?? '')) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Tahun Akademik</label>
                <select name="tahun_akademik_id" class="form-select">
                    <option value="">—</option>
                    <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= (int)old('tahun_akademik_id', $item['tahun_akademik_id'] ?? 0)===(int)$ta['id']?'selected':'' ?>><?= esc($ta['kode']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="">—</option>
                    <?php foreach (['mahasiswa','pt_sendiri','kementerian','hibah','kerjasama','operasional','tridharma','investasi','publikasi','lainnya'] as $v): ?>
                        <option value="<?= $v ?>" <?= old('kategori', $item['kategori'] ?? '')===$v?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$v)) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jumlah (Rp)</label>
                <input type="text" inputmode="numeric" name="jumlah" class="form-control" value="<?= esc(old('jumlah', $item['jumlah'] ?? '')) ?>" placeholder="mis. 50000000">
            </div>
            <div class="col-12">
                <label class="form-label fw-semibold">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2"><?= esc(old('keterangan', $item['keterangan'] ?? '')) ?></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('keuangan') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </div>
    </form>
</div></div>

<?= $this->endSection() ?>
