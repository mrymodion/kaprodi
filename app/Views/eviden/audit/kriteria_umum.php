<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Audit Kriteria <?= esc($kode_kriteria) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
// Mengambil nama kriteria dari elemen array pertama jika tersedia
$namaKriteria = !empty($kriteria) ? $kriteria[0]['nama'] : 'Kriteria ' . $kode_kriteria;
?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-clipboard2-data text-primary me-2"></i> 
            Audit Readiness: <?= esc($kode_kriteria) ?> - <?= esc($namaKriteria) ?>
        </h1>
        <a href="<?= site_url('eviden/ppepp') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Rekap PPEPP
        </a>
    </div>

    <!-- Ringkasan / Rekomendasi Global -->
    <?php if (!empty($summary['rekomendasi'])): ?>
        <div class="row mb-4">
            <div class="col-12">
                <?php foreach ($summary['rekomendasi'] as $rek): ?>
                    <?php 
                    $alertClass = match($rek[0]) {
                        'danger' => 'alert-danger',
                        'warn'   => 'alert-warning',
                        'ok'     => 'alert-success',
                        default  => 'alert-info',
                    };
                    $icon = match($rek[0]) {
                        'danger', 'warn' => 'bi-exclamation-triangle-fill',
                        'ok' => 'bi-check-circle-fill',
                        default => 'bi-info-circle-fill'
                    };
                    ?>
                    <div class="alert <?= $alertClass ?> d-flex align-items-center border-0 shadow-sm">
                        <i class="bi <?= $icon ?> fs-4 me-3"></i>
                        <div><?= $rek[1] ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Kartu Metrik Statistik -->
    <div class="row mb-4">
        <!-- Rata-rata Kesiapan -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm border-start border-4 border-primary h-100 py-2">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-bold mb-1">Rata-rata Kesiapan Kriteria</div>
                    <div class="h4 mb-0 fw-bold text-dark">
                        <?= $summary['avg_readiness'] ?>%
                    </div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar <?= $summary['avg_readiness'] >= 75 ? 'bg-success' : ($summary['avg_readiness'] >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                             role="progressbar" style="width: <?= $summary['avg_readiness'] ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Total Butir -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-4 border-info h-100 py-2">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-bold mb-1">Total Butir Penilaian</div>
                    <div class="h4 mb-0 fw-bold text-dark"><?= $summary['n_butir'] ?> <small class="text-muted fs-6 fw-normal">butir</small></div>
                </div>
            </div>
        </div>
        <!-- Bucket Status -->
        <div class="col-md-5">
            <div class="card border-0 shadow-sm border-start border-4 border-success h-100 py-2">
                <div class="card-body">
                    <div class="text-muted text-uppercase small fw-bold mb-2">Sebaran Status Butir</div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-success rounded-pill px-3 py-2"><i class="bi bi-check-circle me-1"></i> Siap: <?= $summary['bucket']['siap'] ?></span>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i class="bi bi-exclamation-circle me-1"></i> Sebagian: <?= $summary['bucket']['sebagian'] ?></span>
                        <span class="badge bg-danger rounded-pill px-3 py-2"><i class="bi bi-x-circle me-1"></i> Kosong: <?= $summary['bucket']['kosong'] ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Butir -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-list-check me-1"></i> Detail Evaluasi per Butir</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Kode & Judul Butir</th>
                            <th>Readiness Score</th>
                            <th>Checklist Kelengkapan</th>
                            <th>Saran Tindakan Terprioritas</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($butirRows)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada butir penilaian untuk kriteria ini.</td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($butirRows as $b): 
                            $a = $audit[$b['id']];
                            $badge = match($a['status']) {
                                'siap' => 'bg-success',
                                'sebagian' => 'bg-warning text-dark',
                                default => 'bg-danger'
                            };
                        ?>
                        <tr>
                            <td class="ps-4" style="width: 25%;">
                                <strong class="text-dark"><?= esc($b['kode']) ?></strong><br>
                                <span class="text-muted small"><?= esc($b['judul']) ?></span>
                            </td>
                            <td style="width: 15%;">
                                <span class="badge <?= $badge ?> mb-2"><?= strtoupper($a['status']) ?></span>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar <?= $a['readiness_pct'] >= 75 ? 'bg-success' : 'bg-warning' ?>" style="width: <?= $a['readiness_pct'] ?>%"></div>
                                </div>
                                <div class="text-muted small mt-1 fw-bold"><?= $a['readiness_pct'] ?> / 100</div>
                            </td>
                            <td style="width: 20%; font-size: 0.85rem;">
                                <div class="mb-1"><i class="bi <?= $a['has_rubrik'] >= 4 ? 'bi-check-circle-fill text-success' : 'bi-dash-circle text-danger' ?> me-1"></i> Rubrik: <?= $a['has_rubrik'] ?>/4 level</div>
                                <div class="mb-1"><i class="bi <?= $a['has_lkps_ref'] || $a['has_formula'] ? 'bi-check-circle-fill text-success' : 'bi-dash-circle text-danger' ?> me-1"></i> Sumber Data: <?= $a['has_lkps_ref'] ? esc($a['tabel_lkps_ref']) : 'Manual/Formula' ?></div>
                                <div class="mb-1"><i class="bi <?= $a['butir_skor_n'] > 0 ? 'bi-check-circle-fill text-success' : 'bi-dash-circle text-danger' ?> me-1"></i> Telah Dinilai</div>
                                <div><i class="bi <?= $a['ppepp_fase'] == 5 ? 'bi-check-circle-fill text-success' : 'bi-clock-fill text-warning' ?> me-1"></i> Eviden PPEPP: <?= $a['ppepp_fase'] ?>/5 (<?= $a['ppepp_bukti'] ?> Bukti)</div>
                            </td>
                            <td style="width: 30%;">
                                <?php if(empty($a['rekomendasi'])): ?>
                                    <span class="text-success small fw-medium"><i class="bi bi-stars"></i> Sangat baik! Seluruh komponen telah siap.</span>
                                <?php else: ?>
                                    <ul class="mb-0 ps-3 small text-muted">
                                        <?php foreach($a['rekomendasi'] as $r): ?>
                                            <li class="<?= $r[0] === 'warn' ? 'text-danger fw-medium' : 'text-primary' ?> mb-1"><?= $r[1] ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td class="pe-4 text-center" style="width: 10%;">
                                <a href="<?= site_url('eviden/ppepp/' . $b['id'] . '/edit') ?>" class="btn btn-sm btn-primary w-100 mb-2 shadow-sm">
                                    <i class="bi bi-folder-check"></i> PPEPP
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>