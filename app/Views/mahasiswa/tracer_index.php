<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Tracer Study Alumni<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0"><i class="bi bi-briefcase text-primary me-2"></i> Tracer Study Alumni</h4>
        <p class="text-muted small mb-0 mt-1">Pemantauan partisipasi dan hasil kuesioner pelacakan jejak alumni (Data Tabel LKPS 2.B.4 & 2.B.5).</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('mahasiswa') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Mahasiswa
        </a>
    </div>
</div>

<?php if (session()->has('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm"><i class="bi bi-check-circle-fill me-2"></i> <?= session('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->has('info')): ?>
    <div class="alert alert-info alert-dismissible fade show shadow-sm"><i class="bi bi-info-circle-fill me-2"></i> <?= session('info') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Dashboard Statistik -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Total Lulusan</div>
                <div class="d-flex align-items-center">
                    <div class="fs-3 fw-bold text-dark me-3"><?= $stats['total'] ?></div>
                    <i class="bi bi-people text-primary opacity-50 fs-2 ms-auto"></i>
                </div>
                <div class="mt-2 small">
                    <span class="text-success"><i class="bi bi-check-circle"></i> <?= $stats['sudah_isi'] ?> sudah isi</span> &middot; 
                    <span class="text-danger"><i class="bi bi-x-circle"></i> <?= $stats['belum_isi'] ?> belum</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Response Rate</div>
                <div class="d-flex align-items-center">
                    <div class="fs-3 fw-bold text-info me-3"><?= $stats['response_rate'] ?>%</div>
                    <i class="bi bi-pie-chart text-info opacity-50 fs-2 ms-auto"></i>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: <?= $stats['response_rate'] ?>%"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Rata-rata Masa Tunggu</div>
                <div class="d-flex align-items-center">
                    <div class="fs-3 fw-bold text-success me-3"><?= $stats['avg_masa_tunggu'] ?> bln</div>
                    <i class="bi bi-clock-history text-success opacity-50 fs-2 ms-auto"></i>
                </div>
                <div class="text-muted mt-2 small">Waktu tunggu kerja pertama</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
            <div class="card-body">
                <div class="text-muted small fw-bold text-uppercase mb-1">Kesesuaian Bidang (%)</div>
                <div class="d-flex align-items-center">
                    <?php 
                        $totalSesuai = $stats['kesesuaian']['sangat_sesuai'] + $stats['kesesuaian']['sesuai'];
                        $pctSesuai = $stats['sudah_isi'] > 0 ? round(($totalSesuai / $stats['sudah_isi']) * 100) : 0;
                    ?>
                    <div class="fs-3 fw-bold text-warning me-3"><?= $pctSesuai ?>%</div>
                    <i class="bi bi-bullseye text-warning opacity-50 fs-2 ms-auto"></i>
                </div>
                <div class="text-muted mt-2 small">Sangat Sesuai / Sesuai</div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-ul me-1"></i> Daftar Partisipasi Alumni</h6>
        
        <form action="<?= site_url('mahasiswa/tracer/kirim-semua') ?>" method="post" onsubmit="return confirm('Anda yakin ingin mem-broadcast tautan ke SEMUA alumni yang belum mengisi dan memiliki nomor HP?')">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-sm btn-success shadow-sm" <?= $stats['belum_isi'] == 0 ? 'disabled' : '' ?>>
                <i class="bi bi-whatsapp me-1"></i> Broadcast WA (Belum Isi)
            </button>
        </form>
    </div>
    <div class="card-body p-0 table-responsive">
        <table class="table table-hover align-middle mb-0 text-center">
            <thead class="table-light">
                <tr>
                    <th class="text-start ps-3">Nama Alumni</th>
                    <th>NIM</th>
                    <th>Lulusan</th>
                    <th>No. HP</th>
                    <th>Status Tracer</th>
                    <th>Hasil Singkat</th>
                    <th class="text-end pe-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($alumni)): ?>
                    <tr><td colspan="7" class="text-muted py-4">Belum ada data lulusan di prodi ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($alumni as $a): ?>
                        <tr>
                            <td class="text-start ps-3 fw-semibold text-dark"><?= esc($a['nama']) ?></td>
                            <td><code><?= esc($a['nim']) ?></code></td>
                            <td><?= $a['tgl_lulus'] ? date('Y', strtotime($a['tgl_lulus'])) : '-' ?></td>
                            <td><?= esc($a['no_hp'] ?? '-') ?></td>
                            <td>
                                <?php if ($a['tracer_id']): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Sudah Isi</span>
                                    <div class="small text-muted mt-1"><?= date('d M Y', strtotime($a['tgl_isi'])) ?></div>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Belum Isi</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($a['tracer_id']): ?>
                                    <span class="fw-bold"><?= $a['masa_tunggu_bulan'] ?> bln</span>
                                    <div class="small text-muted mt-1"><?= ucwords(str_replace('_', ' ', $a['kesesuaian_bidang_kerja'] ?? '')) ?></div>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3 text-nowrap">
                                <?php if (!$a['tracer_id']): ?>
                                    <!-- Aksi Belum Isi -->
                                    <form action="<?= site_url("mahasiswa/tracer/{$a['id']}/kirim") ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-success" title="Kirim link via WA" <?= empty($a['no_hp']) ? 'disabled' : '' ?>><i class="bi bi-whatsapp"></i></button>
                                    </form>
                                    <a href="<?= site_url("mahasiswa/tracer/{$a['id']}") ?>" class="btn btn-sm btn-outline-primary" title="Isi Manual"><i class="bi bi-pencil"></i></a>
                                <?php else: ?>
                                    <!-- Aksi Sudah Isi -->
                                    <a href="<?= site_url("mahasiswa/tracer/{$a['id']}") ?>" class="btn btn-sm btn-outline-info" title="Lihat / Edit Data"><i class="bi bi-eye"></i> Detail</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>