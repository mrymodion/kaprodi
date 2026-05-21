<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-table"></i> EWMP — Ekuivalen Waktu Mengajar Penuh</h4>
    <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke DTPR
    </a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<!-- Filter TA -->
<form method="get" class="mb-3">
    <div class="d-flex align-items-center gap-2">
        <label class="mb-0 fw-semibold">Tahun Akademik:</label>
        <select name="ta_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
            <?php foreach ($taList as $ta): ?>
                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>>
                    <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>
</form>

<!-- Tabel EWMP -->
<?php if (!empty($ewmp)): ?>
<div class="card mb-3">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span>Data EWMP — <?= esc($taList[array_search($taId, array_column($taList,'id'))]['kode'] ?? '') ?></span>
        <small class="text-muted"><?= count($ewmp) ?> dosen</small>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>NIDN</th>
                    <th>Nama Dosen</th>
                    <th>Pend.</th>
                    <th class="text-center">Pend. PS</th>
                    <th class="text-center">PS Lain (PT ini)</th>
                    <th class="text-center">PT Lain</th>
                    <th class="text-center">Penelitian</th>
                    <th class="text-center">PkM</th>
                    <th class="text-center">Tgs Tambahan</th>
                    <th class="text-center fw-bold">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ewmp as $i => $e): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><code><?= esc($e['nidn'] ?? '-') ?></code></td>
                    <td><?= esc($e['nama']) ?></td>
                    <td><span class="badge bg-<?= ['S3'=>'success','S2'=>'primary','S1'=>'secondary'][$e['pendidikan_terakhir']] ?? 'light text-dark' ?>"><?= esc($e['pendidikan_terakhir'] ?? '-') ?></span></td>
                    <td class="text-center"><?= $e['sks_pendidikan_ps'] ?></td>
                    <td class="text-center"><?= $e['sks_pendidikan_ps_lain_pt'] ?></td>
                    <td class="text-center"><?= $e['sks_pendidikan_pt_lain'] ?></td>
                    <td class="text-center"><?= $e['sks_penelitian'] ?></td>
                    <td class="text-center"><?= $e['sks_pkm'] ?></td>
                    <td class="text-center"><?= $e['sks_tugas_tambahan'] ?></td>
                    <td class="text-center">
                        <?php $tot = $e['total_sks']; ?>
                        <span class="badge <?= $tot >= 12 && $tot <= 16 ? 'bg-success' : 'bg-danger' ?>"><?= $tot ?></span>
                    </td>
                    <td>
                        <a href="<?= site_url("dosen/ewmp/{$e['dosen_id']}?ta_id={$taId}") ?>"
                           class="btn btn-sm btn-outline-primary py-0 px-1" title="Edit"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
            <tfoot class="table-light fw-semibold">
                <tr>
                    <td colspan="4" class="text-end">Total:</td>
                    <?php
                    $cols = ['sks_pendidikan_ps','sks_pendidikan_ps_lain_pt','sks_pendidikan_pt_lain','sks_penelitian','sks_pkm','sks_tugas_tambahan','total_sks'];
                    foreach ($cols as $col): ?>
                        <td class="text-center"><?= array_sum(array_column($ewmp, $col)) ?></td>
                    <?php endforeach ?>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-footer text-muted small">
        <i class="bi bi-info-circle"></i> Standar EWMP: 12–16 SKS/semester. Kolom hijau = normal, merah = perlu perhatian.
    </div>
</div>
<?php endif ?>

<!-- Dosen belum EWMP -->
<?php if (!empty($belum)): ?>
<div class="card border-warning">
    <div class="card-header bg-warning-subtle fw-semibold">
        <i class="bi bi-exclamation-triangle text-warning"></i>
        Dosen Belum Input EWMP (<?= count($belum) ?>)
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($belum as $d): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <code><?= esc($d['nidn'] ?? '-') ?></code>
                    <strong class="ms-2"><?= esc($d['nama']) ?></strong>
                    <small class="text-muted ms-1"><?= esc($d['jabatan_akademik'] ?? '-') ?></small>
                </span>
                <a href="<?= site_url("dosen/ewmp/{$d['id']}?ta_id={$taId}") ?>" class="btn btn-sm btn-warning">
                    <i class="bi bi-plus-circle"></i> Input EWMP
                </a>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php elseif (empty($ewmp)): ?>
    <div class="alert alert-info">Pilih tahun akademik dan mulai input EWMP.</div>
<?php else: ?>
    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> Semua DTPR sudah mengisi EWMP untuk TA ini.</div>
<?php endif ?>

<?= $this->endSection() ?>
