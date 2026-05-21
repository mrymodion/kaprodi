<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($mk) ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-journal-text"></i> <?= $isEdit ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' ?> — <?= esc($kuri['kode']) ?></h4>
    <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-mk") ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<form method="post" action="<?= esc($action) ?>">
    <?= csrf_field() ?>


    <div class="row g-4">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white fw-semibold">Data Mata Kuliah</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode MK <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control" required maxlength="20"
                                placeholder="mis. MI301"
                                value="<?= esc(old('kode', $mk['kode'] ?? '')) ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?= esc(old('nama', $mk['nama'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SKS Teori</label>
                            <input type="number" name="sks_teori" class="form-control sks-inp" min="0" max="6"
                                value="<?= esc(old('sks_teori', $mk['sks_teori'] ?? 0)) ?>" oninput="updateTotal()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SKS Praktik</label>
                            <input type="number" name="sks_praktek" class="form-control sks-inp" min="0" max="6"
                                value="<?= esc(old('sks_praktek', $mk['sks_praktek'] ?? 0)) ?>" oninput="updateTotal()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">SKS Lapangan</label>
                            <input type="number" name="sks_lapangan" class="form-control sks-inp" min="0" max="6"
                                value="<?= esc(old('sks_lapangan', $mk['sks_lapangan'] ?? 0)) ?>" oninput="updateTotal()">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total SKS</label>
                            <input type="text" id="totalSks" class="form-control bg-light" readonly value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Semester <span class="text-danger">*</span></label>
                            <input type="number" name="semester" class="form-control" required min="1" max="8"
                                value="<?= esc(old('semester', $mk['semester'] ?? '')) ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select">
                                <option value="wajib"   <?= old('jenis', $mk['jenis'] ?? 'wajib') === 'wajib' ? 'selected' : '' ?>>Wajib</option>
                                <option value="pilihan" <?= old('jenis', $mk['jenis'] ?? '') === 'pilihan' ? 'selected' : '' ?>>Pilihan</option>
                                <option value="mbkm"    <?= old('jenis', $mk['jenis'] ?? '') === 'mbkm' ? 'selected' : '' ?>>MBKM</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Path RPS (opsional)</label>
                            <input type="text" name="rps_path" class="form-control"
                                value="<?= esc(old('rps_path', $mk['rps_path'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <?php if (!empty($cpl)): ?>
            <div class="card">
                <div class="card-header bg-success text-white fw-semibold">CPL yang Didukung MK Ini</div>
                <div class="card-body p-2">
                    <?php
                    $aspekColors = ['sikap'=>'success','pengetahuan'=>'primary','keterampilan_umum'=>'info','keterampilan_khusus'=>'warning'];
                    $aspekGroups = [];
                    foreach ($cpl as $c) { $aspekGroups[$c['aspek']][] = $c; }
                    foreach (['sikap','pengetahuan','keterampilan_umum','keterampilan_khusus'] as $aspek):
                        if (empty($aspekGroups[$aspek])) continue;
                        $color = $aspekColors[$aspek];
                    ?>
                    <div class="mb-2">
                        <div class="small fw-semibold text-<?= $color ?> px-2 py-1"><?= \App\Models\CplModel::aspekLabel($aspek) ?></div>
                        <?php foreach ($aspekGroups[$aspek] as $c): ?>
                        <div class="form-check px-3 py-1">
                            <input class="form-check-input" type="checkbox"
                                name="cpl_ids[]" value="<?= $c['id'] ?>"
                                id="cpl_<?= $c['id'] ?>"
                                <?= in_array($c['id'], $checked) ? 'checked' : '' ?>>
                            <label class="form-check-label small" for="cpl_<?= $c['id'] ?>">
                                <span class="badge bg-<?= $color ?>"><?= esc($c['kode']) ?></span>
                                <?= esc(mb_substr($c['deskripsi'],0,60)) ?>...
                            </label>
                        </div>
                        <?php endforeach ?>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-warning">Tambahkan CPL terlebih dahulu sebelum membuat mata kuliah.</div>
            <?php endif ?>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> <?= $isEdit ? 'Simpan' : 'Tambah MK' ?></button>
        <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-mk") ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<script>
function updateTotal() {
    const inputs = document.querySelectorAll('.sks-inp');
    let t = 0; inputs.forEach(i => t += parseInt(i.value || 0));
    document.getElementById('totalSks').value = t;
}
document.addEventListener('DOMContentLoaded', updateTotal);
</script>

<?= $this->endSection() ?>
