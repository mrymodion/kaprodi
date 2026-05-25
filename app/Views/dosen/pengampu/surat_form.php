<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Surat Tugas Mengajar</h4>
    <a href="<?= site_url('dosen/pengampu?ta_id=' . $taId) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Daftar</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Data Surat &mdash; <?= esc($namaDosen) ?></div>
            <div class="card-body">
                <?php if (empty($list)): ?>
                    <div class="alert alert-warning small mb-0"><i class="bi bi-exclamation-triangle"></i> Dosen ini belum memiliki mata kuliah diampu pada TA terpilih. <a href="<?= site_url("dosen/pengampu/{$dosen['id']}/kelola?ta_id={$taId}") ?>">Input MK dulu</a>.</div>
                <?php else: ?>
                <form method="post" action="<?= site_url("dosen/pengampu/{$dosen['id']}/surat") ?>" target="_blank">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select form-select-sm" required onchange="location.href='<?= site_url("dosen/pengampu/{$dosen['id']}/surat") ?>?ta_id='+this.value">
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Nomor Surat</label>
                        <input type="text" name="nomor" class="form-control form-control-sm" placeholder="mis. 123/ST/PRODI/<?= date('Y') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Dasar / Pertimbangan (opsional)</label>
                        <textarea name="dasar" class="form-control form-control-sm" rows="2" placeholder="mis. Kalender akademik & beban kerja dosen semester berjalan"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label small mb-0">Kota</label>
                            <input type="text" name="kota" class="form-control form-control-sm" value="<?= esc($kop['pt_kota'] ?? '', 'attr') ?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label small mb-0">Tanggal</label>
                            <input type="text" name="tanggal" class="form-control form-control-sm" value="<?= date('d-m-Y') ?>">
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Penandatangan (Kaprodi)</label>
                        <input type="text" name="signer_nama" class="form-control form-control-sm" value="<?= esc($signer['nama'] ?? '', 'attr') ?>" placeholder="Nama kaprodi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small mb-0">NIP / NIDN Penandatangan</label>
                        <input type="text" name="signer_nip" class="form-control form-control-sm" value="<?= esc($signer['nip'] ?? '', 'attr') ?>">
                    </div>
                    <button class="btn btn-success"><i class="bi bi-download"></i> Generate & Unduh PDF</button>
                </form>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Mata Kuliah Diampu</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light"><tr><th>Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Kelas</th><th class="text-center">Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">—</td></tr>
                    <?php else: $g = 0; foreach ($list as $r): $g += (float) $r['total_sks']; ?>
                        <tr><td><small><?= esc($r['kode_mk'] ?? '-') ?></small></td><td><?= esc($r['nama_mk']) ?></td><td class="text-center"><?= (float) $r['sks'] ?></td><td class="text-center"><?= (int) $r['jumlah_kelas'] ?></td><td class="text-center"><?= (float) $r['total_sks'] ?></td></tr>
                    <?php endforeach ?>
                        <tr class="table-light fw-bold"><td class="text-end" colspan="4">Total SKS</td><td class="text-center"><?= $g ?></td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
