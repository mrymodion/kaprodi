<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$badge = fn($s) => $s === 'siap' ? 'success' : ($s === 'sebagian' ? 'warning text-dark' : 'danger');
$icon  = fn($s) => $s === 'siap' ? 'bi-check-circle-fill' : ($s === 'sebagian' ? 'bi-exclamation-triangle-fill' : 'bi-x-circle-fill');
$alert = fn($k) => $k === 'ok' ? 'success' : ($k === 'warn' ? 'warning' : ($k === 'info' ? 'info' : 'danger'));
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-clipboard-data"></i> Audit Otomatisasi &mdash; <span class="badge bg-primary">A</span> Kondisi Eksternal</h4>
    <a href="<?= site_url('eviden/ppepp') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Eviden PPEPP</a>
</div>

<div class="alert alert-light border small">
    <strong><i class="bi bi-info-circle"></i> Sprint 1 dari rencana 4-sprint otomatisasi Kriteria A.</strong>
    Halaman ini hanya membaca state — tidak mengubah data. Tujuannya menentukan apa yang perlu dibangun sebelum lanjut ke Sprint 2 (LKPS auto-fill), Sprint 3 (AI narasi LED), dan Sprint 4 (skor otomatis).
</div>

<?php if (empty($instrumenIds)): ?>
    <div class="alert alert-danger"><i class="bi bi-x-octagon"></i> Prodi belum memiliki instrumen LAM aktif. Tetapkan instrumen LAM dulu sebelum melanjutkan audit.</div>
<?php elseif (empty($kriteria)): ?>
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Instrumen aktif Anda tidak punya kriteria dengan jenis <code>kondisi_eksternal</code>. Kriteria A perlu di-<em>seed</em> ke `lam_kriteria` (jenis = kondisi_eksternal) sebagai langkah awal Sprint 2.</div>
<?php else: ?>

<div class="row g-3 mb-3">
    <div class="col-md-3">
        <div class="card h-100"><div class="card-body text-center">
            <div class="text-muted small">Jumlah Butir</div>
            <div class="display-6 fw-bold"><?= $summary['n_butir'] ?></div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card h-100"><div class="card-body text-center">
            <div class="text-muted small">Rata-rata Readiness</div>
            <div class="display-6 fw-bold"><?= $summary['avg_readiness'] ?>%</div>
            <div class="progress mt-2" style="height:6px"><div class="progress-bar bg-<?= $summary['avg_readiness'] >= 75 ? 'success' : ($summary['avg_readiness'] >= 35 ? 'warning' : 'danger') ?>" style="width:<?= $summary['avg_readiness'] ?>%"></div></div>
        </div></div>
    </div>
    <div class="col-md-6">
        <div class="card h-100"><div class="card-body">
            <div class="text-muted small mb-1">Distribusi Status Butir</div>
            <div class="d-flex gap-2 flex-wrap">
                <span class="badge bg-success fs-6">Siap: <?= $summary['bucket']['siap'] ?></span>
                <span class="badge bg-warning text-dark fs-6">Sebagian: <?= $summary['bucket']['sebagian'] ?></span>
                <span class="badge bg-danger fs-6">Kosong: <?= $summary['bucket']['kosong'] ?></span>
            </div>
            <hr class="my-2">
            <div class="small">
                <div><i class="bi bi-x-circle"></i> Tanpa rubrik lengkap: <strong><?= $summary['tanpa_rubrik'] ?></strong></div>
                <div><i class="bi bi-x-circle"></i> Tanpa sumber data: <strong><?= $summary['tanpa_input'] ?></strong></div>
                <div><i class="bi bi-x-circle"></i> Tanpa PPEPP: <strong><?= $summary['tanpa_ppepp'] ?></strong></div>
            </div>
        </div></div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-compass"></i> Rekomendasi Global &mdash; Arah Sprint 2-4</div>
    <div class="card-body py-2">
        <?php foreach ($summary['rekomendasi'] as [$k, $msg]): ?>
            <div class="alert alert-<?= $alert($k) ?> small py-2 mb-2"><i class="bi bi-arrow-right-circle"></i> <?= $msg ?></div>
        <?php endforeach ?>
    </div>
</div>

<?php foreach ($kriteria as $kr): ?>
    <div class="card mb-3">
        <div class="card-header bg-light fw-semibold">
            <span class="badge bg-secondary"><?= esc($kr['kode']) ?></span> <?= esc($kr['nama']) ?>
        </div>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:80px">Kode</th>
                        <th>Judul Butir</th>
                        <th class="text-center" style="width:90px">Rubrik</th>
                        <th class="text-center" style="width:90px">Sumber Data</th>
                        <th class="text-center" style="width:90px">Skor</th>
                        <th class="text-center" style="width:100px">PPEPP</th>
                        <th class="text-center" style="width:80px">Bukti</th>
                        <th class="text-center" style="width:110px">Status</th>
                        <th>Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($butirRows as $b): if ($b['lam_kriteria_id'] != $kr['id']) continue; $a = $audit[(int)$b['id']]; ?>
                    <tr>
                        <td><small><?= esc($b['kode']) ?></small></td>
                        <td>
                            <?= esc($b['judul']) ?>
                            <?php if (!empty($b['deskripsi'])): ?><div class="text-muted small"><?= esc(mb_strimwidth($b['deskripsi'], 0, 140, '…')) ?></div><?php endif ?>
                        </td>
                        <td class="text-center">
                            <?php if ($a['has_rubrik'] >= 4): ?><i class="bi bi-check-circle-fill text-success" title="Lengkap"></i>
                            <?php elseif ($a['has_rubrik'] > 0): ?><span class="badge bg-warning text-dark"><?= $a['has_rubrik'] ?>/4</span>
                            <?php else: ?><i class="bi bi-x-circle-fill text-danger" title="Belum ada"></i><?php endif ?>
                        </td>
                        <td class="text-center">
                            <?php if ($a['has_lkps_ref']): ?><span class="badge bg-success" title="<?= esc($a['tabel_lkps_ref']) ?>"><?= esc($a['tabel_lkps_ref']) ?></span>
                            <?php elseif ($a['has_formula']): ?><span class="badge bg-info">Formula</span>
                            <?php elseif ($b['tipe_data'] === 'kualitatif'): ?><span class="badge bg-secondary">Kualitatif</span>
                            <?php else: ?><i class="bi bi-x-circle-fill text-danger" title="Belum didefinisikan"></i><?php endif ?>
                        </td>
                        <td class="text-center"><?php if ($a['butir_skor_n'] > 0): ?><i class="bi bi-check-circle-fill text-success"></i><?php else: ?><i class="bi bi-dash-circle text-muted"></i><?php endif ?></td>
                        <td class="text-center">
                            <div class="progress" style="height:6px"><div class="progress-bar bg-<?= $a['ppepp_fase']==5 ? 'success' : ($a['ppepp_fase']>0 ? 'warning' : 'secondary') ?>" style="width:<?= ($a['ppepp_fase']/5)*100 ?>%"></div></div>
                            <small><?= $a['ppepp_fase'] ?>/5</small>
                        </td>
                        <td class="text-center"><small><?= $a['ppepp_bukti'] ?: '—' ?></small></td>
                        <td class="text-center">
                            <span class="badge bg-<?= $badge($a['status']) ?>"><i class="bi <?= $icon($a['status']) ?>"></i> <?= ucfirst($a['status']) ?></span>
                            <div class="small text-muted"><?= $a['readiness_pct'] ?>%</div>
                        </td>
                        <td>
                            <?php if (empty($a['rekomendasi'])): ?>
                                <small class="text-success"><i class="bi bi-check"></i> Tidak ada gap</small>
                            <?php else: foreach ($a['rekomendasi'] as [$k, $msg]): ?>
                                <div class="small text-<?= $k==='warn' ? 'warning' : 'muted' ?>"><i class="bi bi-arrow-right"></i> <?= $msg ?></div>
                            <?php endforeach; endif ?>
                            <a href="<?= site_url("eviden/ppepp/{$b['id']}/edit") ?>" class="btn btn-sm btn-outline-primary py-0 px-1 mt-1"><i class="bi bi-pencil-square"></i> Isi PPEPP</a>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endforeach ?>

<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-bookmark-check"></i> Definition of Done Sprint 1</div>
    <div class="card-body small">
        <div class="row g-2">
            <div class="col-md-6">
                <strong>Output yang dihasilkan halaman ini:</strong>
                <ul class="mb-0 small">
                    <li>Jumlah butir Kondisi Eksternal di instrumen aktif: <strong><?= $summary['n_butir'] ?></strong></li>
                    <li>Gap rubrik / data / PPEPP per butir teridentifikasi</li>
                    <li>Daftar sumber otomatis yang relevan per butir teridentifikasi</li>
                    <li>Arah Sprint 2-4 tervalidasi (tidak menebak buta)</li>
                </ul>
            </div>
            <div class="col-md-6">
                <strong>Keputusan yang harus dibuat sebelum Sprint 2:</strong>
                <ul class="mb-0 small">
                    <li>Apakah perlu seeding butir + rubrik baru?</li>
                    <li>Skema input data Kondisi Eksternal yang terstruktur (PESTEL? SWOT? Demografi pasar kerja?)</li>
                    <li>Sumber eksternal yang akan dipakai (input manual, BPS API, dapodik, dll)?</li>
                    <li>Apakah ada butir kuantitatif yang membutuhkan tabel LKPS baru?</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php endif ?>
<?= $this->endSection() ?>
