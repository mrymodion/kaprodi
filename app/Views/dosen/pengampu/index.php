<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-easel2"></i> Penugasan Mengajar</h4>
    <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-people"></i> Data Dosen</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Input mata kuliah yang diampu tiap dosen per tahun akademik, lalu cetak <strong>Surat Tugas Mengajar</strong> (PDF) per dosen. Data ini juga dipakai untuk menghitung SKS Pendidikan EWMP secara otomatis.</div>

<div class="mb-3" style="max-width:320px">
    <label class="form-label small mb-0 fw-semibold">Tahun Akademik</label>
    <select class="form-select form-select-sm" onchange="location.href='<?= site_url('dosen/pengampu') ?>?ta_id='+this.value">
        <option value="">— Pilih —</option>
        <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
        <?php endforeach ?>
    </select>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Nama Dosen</th><th>NIDN</th><th class="text-center">Jml MK</th><th class="text-center">Total SKS</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                <?php if (empty($dosen)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data dosen pada prodi aktif.</td></tr>
                <?php else: foreach ($dosen as $i => $d): $r = $rekap[(int) $d['id']] ?? null; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc(trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : ''))) ?></td>
                        <td><small><?= esc($d['nidn'] ?? '-') ?></small></td>
                        <td class="text-center"><?= $r ? (int) $r['jml'] : '<span class="text-muted">0</span>' ?></td>
                        <td class="text-center"><?= $r ? (float) $r['total_sks'] : '<span class="text-muted">0</span>' ?></td>
                        <td class="text-end">
                            <a href="<?= site_url("dosen/pengampu/{$d['id']}/kelola?ta_id={$taId}") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil-square"></i> Kelola MK</a>
                            <a href="<?= site_url("dosen/pengampu/{$d['id']}/surat?ta_id={$taId}") ?>" class="btn btn-sm btn-outline-success py-0 px-1"><i class="bi bi-file-earmark-pdf"></i> Surat Tugas</a>
                        </td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
