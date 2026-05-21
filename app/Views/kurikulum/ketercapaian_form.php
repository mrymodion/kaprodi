<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-bar-chart"></i> Input Ketercapaian CPL — <?= esc($kuri['kode']) ?></h4>
    <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-ket") ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<form method="post" action="<?= site_url("kurikulum/{$kuri['id']}/ketercapaian/save") ?>">
    <?= csrf_field() ?>

    <!-- Pilih TA -->
    <div class="d-flex align-items-center gap-3 mb-3">
        <label class="fw-semibold mb-0">Tahun Akademik:</label>
        <select name="tahun_akademik_id" class="form-select form-select-sm w-auto" onchange="window.location='?ta_id='+this.value">
            <?php foreach ($taList as $ta): ?>
                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>>
                    <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <?php if (empty($rows)): ?>
        <div class="alert alert-warning">
            Belum ada pasangan MK-CPL. Lengkapi <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-peta") ?>">Peta CPL-MK</a> terlebih dahulu.
        </div>
    <?php else: ?>
    <div class="card">
        <div class="card-header bg-white fw-semibold">
            Input % Ketercapaian CPL per Mata Kuliah
            <span class="text-muted small ms-2">(0–100)</span>
        </div>
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Sem</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>CPL</th>
                        <th style="width:140px">% Tercapai</th>
                        <th style="width:110px">Jml Mhs</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $prevMk = null;
                foreach ($rows as $r):
                    $key = "{$r['mk_id']}_{$r['cpl_id']}";
                    $isNewMk = $r['mk_kode'] !== $prevMk;
                    $prevMk  = $r['mk_kode'];
                ?>
                    <tr class="<?= $isNewMk ? 'table-light border-top' : '' ?>">
                        <td><?= $isNewMk ? $r['semester'] : '' ?></td>
                        <td><?= $isNewMk ? '<code>'.esc($r['mk_kode']).'</code>' : '' ?></td>
                        <td><?= $isNewMk ? esc($r['mk_nama']) : '' ?></td>
                        <td><span class="badge bg-primary"><?= esc($r['cpl_kode']) ?></span></td>
                        <td>
                            <div class="input-group input-group-sm">
                                <input type="number" name="ket[<?= $key ?>][pct]"
                                    class="form-control form-control-sm pct-inp"
                                    min="0" max="100" step="0.1"
                                    value="<?= esc($r['persen_tercapai'] ?? '') ?>"
                                    placeholder="0–100">
                                <span class="input-group-text">%</span>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="ket[<?= $key ?>][mhs]"
                                class="form-control form-control-sm"
                                min="0" value="<?= esc($r['jumlah_mhs'] ?? '') ?>"
                                placeholder="jml mhs">
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer small text-muted">
            Target LAM: rata-rata ketercapaian CPL ≥ 75% → skor 4 (Sangat Baik).
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-floppy"></i> Simpan Ketercapaian
        </button>
    </div>
    <?php endif ?>
</form>

<?= $this->endSection() ?>
