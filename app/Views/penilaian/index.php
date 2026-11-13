<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Penilaian Akreditasi<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-ui-checks text-primary me-2"></i> Penilaian Akreditasi Mandiri
        </h1>
        <form action="<?= site_url('penilaian/run-auto') ?>" method="POST" class="d-flex align-items-center gap-2" onsubmit="return confirm('Jalankan ulang perhitungan skor otomatis untuk kriteria kuantitatif? (Ini tidak akan menimpa skor manual Anda)');">
            <?= csrf_field() ?>
            <select name="ta_id" class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?ta_id='+this.value">
                <option value="">-- Semua Tahun (Akumulasi) --</option>
                <?php foreach ($taList as $ta): ?>
                    <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-warning shadow-sm fw-bold btn-sm">
                <i class="bi bi-magic me-1"></i> Jalankan Auto-Skor Kuantitatif
            </button>
        </form>
    </div>

    <?php if (session('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= esc(session('success')) ?></div>
    <?php endif ?>

    <div class="row mb-4">
        <!-- Skor Akhir Card -->
        <div class="col-md-12">
            <div class="card border-0 shadow-sm bg-primary text-white text-center py-4 rounded-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);">
                <div class="card-body">
                    <h5 class="fw-normal opacity-75 mb-2">Nilai Akhir Sementara</h5>
                    <h1 class="display-3 fw-bold mb-0"><?= number_format($total['nilai_akhir'] ?? 0, 2) ?></h1>
                    <p class="fs-5 mt-2 mb-0">Predikat: <strong><?= esc($total['predikat'] ?? 'Tidak Terakreditasi') ?></strong></p>
                    <div class="mt-3 small opacity-75">
                        (Skor Maksimal: <?= number_format($total['max_nilai'] ?? 400, 0) ?> &bull; Persentase: <?= number_format($total['pct'] ?? 0, 1) ?>%)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Kriteria -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-dark"><i class="bi bi-list-ol me-1"></i> Rincian Skor per Kriteria</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">Kode & Kriteria</th>
                            <th class="text-center">Progres Input</th>
                            <th class="text-center">Skor Rata-Rata</th>
                            <th class="text-center">Bobot Kriteria</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kriteria as $k): ?>
                            <tr>
                                <td class="ps-4">
                                    <strong class="d-block text-dark"><?= esc($k['kode']) ?></strong>
                                    <span class="text-muted small"><?= esc($k['nama']) ?></span>
                                </td>
                                <td class="text-center" style="width: 20%;">
                                    <?php $pct = $k['jml_butir'] > 0 ? round(($k['jml_isi'] / $k['jml_butir']) * 100) : 0; ?>
                                    <div class="progress mb-1" style="height: 6px;">
                                        <div class="progress-bar <?= $pct === 100 ? 'bg-success' : 'bg-primary' ?>" style="width: <?= $pct ?>%"></div>
                                    </div>
                                    <small class="text-muted fw-bold"><?= $k['jml_isi'] ?> / <?= $k['jml_butir'] ?> Butir</small>
                                </td>
                                <td class="text-center" style="width: 15%;">
                                    <span class="badge <?= ($k['skor_rata'] >= 3.0) ? 'bg-success' : (($k['skor_rata'] >= 2.0) ? 'bg-warning text-dark' : 'bg-danger') ?> fs-6 rounded-pill px-3">
                                        <?= $k['skor_rata'] !== null ? number_format($k['skor_rata'], 2) : '-' ?>
                                    </span>
                                </td>
                                <td class="text-center text-muted" style="width: 15%;">
                                    <?= number_format($k['total_bobot'], 2) ?>
                                </td>
                                <td class="pe-4 text-end" style="width: 15%;">
                                    <a href="<?= site_url('penilaian/kriteria/' . $k['id']) ?><?= $taId ? '?ta_id=' . $taId : '' ?>" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-3">
                                        <i class="bi bi-pencil-square"></i> Nilai
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