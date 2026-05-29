<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Surat Tugas Mengajar</h4>
    <a href="<?= site_url('akun/ewmp/pengampu?ta_id=' . $taId) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> MK Diampu</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="mb-3" style="max-width:320px">
    <label class="form-label small mb-0 fw-semibold">Tahun Akademik</label>
    <select class="form-select form-select-sm" onchange="location.href='<?= site_url('akun/ewmp/surat') ?>?ta_id='+this.value">
        <option value="">— Pilih —</option>
        <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
        <?php endforeach ?>
    </select>
</div>

<?php if ($taId): ?>
<div class="card mb-3">
    <div class="card-header bg-white fw-semibold">Mata Kuliah Diampu — <?= esc($namaDosen) ?></div>
    <div class="card-body p-0">
        <?php if (empty($list)): ?>
            <div class="p-3 text-muted small"><i class="bi bi-exclamation-triangle"></i> Belum ada mata kuliah diampu pada TA ini. <a href="<?= site_url('akun/ewmp/pengampu?ta_id=' . $taId) ?>">Tambah MK</a>.</div>
        <?php else: ?>
        <table class="table table-sm mb-0 align-middle">
            <thead class="table-light"><tr><th>Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Kelas</th><th class="text-center">Total SKS</th></tr></thead>
            <tbody>
            <?php $g = 0; foreach ($list as $r): $g += (float) $r['total_sks']; ?>
                <tr>
                    <td><small><?= esc($r['kode_mk'] ?? '-') ?></small></td>
                    <td><?= esc($r['nama_mk']) ?></td>
                    <td class="text-center"><?= (float) $r['sks'] ?></td>
                    <td class="text-center"><?= (int) $r['jumlah_kelas'] ?></td>
                    <td class="text-center"><?= (float) $r['total_sks'] ?></td>
                </tr>
            <?php endforeach ?>
            <tr class="table-light fw-bold"><td class="text-end" colspan="4">Total</td><td class="text-center"><?= $g ?></td></tr>
            </tbody>
        </table>
        <?php endif ?>
    </div>
</div>

<?php if (!empty($list)): ?>
<?php $issued = !empty($meta['nomor']); ?>
<?php if ($issued): ?>
<div class="alert alert-success small py-2 mb-2">
    <i class="bi bi-patch-check"></i> Surat tugas TA ini sudah diterbitkan oleh Kaprodi &mdash; Nomor <strong><?= esc($meta['nomor']) ?></strong><?= !empty($meta['tanggal']) ? ' &middot; tanggal ' . esc($meta['tanggal']) : '' ?>.
</div>
<a href="<?= site_url('akun/ewmp/surat/unduh?ta_id=' . $taId) ?>" class="btn btn-success" target="_blank">
    <i class="bi bi-download"></i> Unduh Surat Tugas PDF
</a>
<?php else: ?>
<div class="alert alert-warning small mb-0">
    <i class="bi bi-hourglass-split"></i> Surat tugas TA ini <strong>belum diterbitkan</strong> oleh Kaprodi. Mohon menunggu Kaprodi menerbitkan surat tugas (mengisi nomor surat). Setelah diterbitkan, tombol unduh akan tersedia di sini.
</div>
<?php endif ?>
<?php endif ?>
<?php endif ?>

<?= $this->endSection() ?>
