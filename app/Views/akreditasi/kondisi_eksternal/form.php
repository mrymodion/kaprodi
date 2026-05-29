<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$KAT = \App\Models\KondisiEksternalModel::KATEGORI;
$DMP = \App\Models\KondisiEksternalModel::DAMPAK_LABELS;
$selectedKategori = $row['kategori'] ?? ($kategoriDefault ?? array_key_first($KAT));
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> <?= $mode === 'edit' ? 'Edit' : 'Tambah' ?> Entri Kondisi Eksternal</h4>
    <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Daftar</a>
</div>

<form method="post" action="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="card card-body shadow-sm">
    <?= csrf_field() ?>
    <?php if ($mode === 'edit'): ?><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><?php endif ?>

    <div class="row g-3">
        <div class="col-md-5">
            <label class="form-label small fw-semibold mb-1">Kategori <span class="text-danger">*</span></label>
            <select name="kategori" class="form-select form-select-sm" required>
                <?php foreach ($KAT as $key => $info): ?>
                    <option value="<?= $key ?>" <?= $selectedKategori === $key ? 'selected' : '' ?>>[<?= esc($info['grup']) ?>] <?= esc($info['label']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Dampak <span class="text-danger">*</span></label>
            <select name="dampak" class="form-select form-select-sm" required>
                <?php foreach ($DMP as $key => $info): ?>
                    <option value="<?= $key ?>" <?= ($row['dampak'] ?? 'sedang') === $key ? 'selected' : '' ?>><?= esc($info['label']) ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Tahun Relevansi</label>
            <input type="number" name="tahun_relevansi" class="form-control form-control-sm" min="2000" max="2100" value="<?= esc($row['tahun_relevansi'] ?? '', 'attr') ?>" placeholder="<?= date('Y') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Urutan Tampil</label>
            <input type="number" name="urutan" class="form-control form-control-sm" value="<?= esc($row['urutan'] ?? 0, 'attr') ?>">
        </div>

        <div class="col-12">
            <label class="form-label small fw-semibold mb-1">Judul <span class="text-danger">*</span></label>
            <input type="text" name="judul" class="form-control form-control-sm" required maxlength="250" value="<?= esc(old('judul', $row['judul'] ?? ''), 'attr') ?>" placeholder="mis. Permendikbudristek 53/2023 tentang Penjaminan Mutu PT">
        </div>

        <div class="col-12">
            <label class="form-label small fw-semibold mb-1">Deskripsi <span class="text-danger">*</span></label>
            <textarea name="deskripsi" class="form-control form-control-sm" rows="4" required placeholder="Jelaskan secara ringkas: apa faktor eksternal ini, mengapa relevan untuk prodi, dampaknya terhadap penyelenggaraan pendidikan."><?= esc(old('deskripsi', $row['deskripsi'] ?? '')) ?></textarea>
        </div>

        <div class="col-12">
            <label class="form-label small fw-semibold mb-1">Respons / Rencana Tindak Lanjut Prodi</label>
            <textarea name="respons_prodi" class="form-control form-control-sm" rows="3" placeholder="Apa yang akan/sudah dilakukan prodi untuk merespons faktor eksternal ini? (kebijakan, kurikulum, program, kerjasama, dll)"><?= esc(old('respons_prodi', $row['respons_prodi'] ?? '')) ?></textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label small fw-semibold mb-1">Sumber Referensi</label>
            <input type="text" name="sumber_referensi" class="form-control form-control-sm" maxlength="250" value="<?= esc(old('sumber_referensi', $row['sumber_referensi'] ?? ''), 'attr') ?>" placeholder="mis. BPS 2024, Kepmendikbud 53/2023, Laporan APTIKOM">
        </div>
        <div class="col-md-6">
            <label class="form-label small fw-semibold mb-1">Link Bukti (URL)</label>
            <input type="url" name="link_bukti" class="form-control form-control-sm" maxlength="500" value="<?= esc(old('link_bukti', $row['link_bukti'] ?? ''), 'attr') ?>" placeholder="https://...">
            <div class="form-text">Akan otomatis dipakai sebagai bukti di PPEPP (Tarik Eviden Otomatis).</div>
        </div>
    </div>

    <div class="mt-3">
        <button class="btn btn-success"><i class="bi bi-check"></i> Simpan</button>
        <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<?= $this->endSection() ?>
