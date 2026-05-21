<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$canInput   = auth()->user()->can('penilaian.input');
$canApprove = auth()->user()->can('penilaian.approve');
$predikatColor = ['Unggul'=>'success','Baik Sekali'=>'primary','Baik'=>'info','Cukup'=>'warning','Kurang'=>'danger','-'=>'secondary'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-clipboard-check"></i> Penilaian LAM</h4>
        <?php if ($instrumen): ?>
        <small class="text-muted"><?= esc($instrumen['nama_lembaga']) ?> — <?= esc($instrumen['nama']) ?></small>
        <?php endif ?>
    </div>
    <div class="d-flex gap-2">
        <!-- Filter TA -->
        <form method="get" class="d-flex gap-2 align-items-center">
            <select name="ta" class="form-select form-select-sm" style="width:160px" onchange="this.form.submit()">
                <option value="">— Tahun Studi —</option>
                <?php foreach ($taList as $ta): ?>
                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
                <?php endforeach ?>
            </select>
        </form>
        <?php if ($canInput && $instrumen): ?>
        <form method="post" action="<?= site_url('penilaian/run-auto') ?>">
            <?= csrf_field() ?>
            <?php if ($taId): ?><input type="hidden" name="tahun_akademik_id" value="<?= $taId ?>"><?php endif ?>
            <button type="submit" class="btn btn-sm btn-outline-primary" onclick="return confirm('Jalankan auto-skor dari database?\nSkor manual tidak akan ditimpa.')">
                <i class="bi bi-cpu"></i> Auto-Skor
            </button>
        </form>
        <?php endif ?>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php if (!$instrumen): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Prodi belum memiliki instrumen LAM yang aktif. Hubungi superadmin.</div>
<?= $this->endSection() ?>
<?php return ?>
<?php endif ?>

<!-- Total Skor Card -->
<div class="card mb-4" style="border-left:4px solid <?= $total['predikat']==='Unggul'?'#198754':($total['predikat']==='-'?'#6c757d':'#0d6efd') ?>">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <div class="display-5 fw-bold text-<?= $predikatColor[$total['predikat']] ?? 'secondary' ?>">
                    <?= number_format($total['nilai_akhir'], 1) ?>
                </div>
                <div class="text-muted small">dari <?= number_format($total['max_nilai']) ?> poin</div>
                <span class="badge bg-<?= $predikatColor[$total['predikat']] ?? 'secondary' ?> fs-6 mt-1">
                    <?= $total['predikat'] ?>
                </span>
            </div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between mb-1">
                    <span class="fw-semibold">Total Nilai Akreditasi</span>
                    <span class="fw-bold"><?= $total['pct'] ?>%</span>
                </div>
                <div class="progress" style="height:20px">
                    <?php
                    $pct = $total['pct'];
                    $barColor = $pct >= 85 ? 'success' : ($pct >= 70 ? 'primary' : ($pct >= 55 ? 'info' : ($pct >= 40 ? 'warning' : 'danger')));
                    ?>
                    <div class="progress-bar bg-<?= $barColor ?> progress-bar-striped" style="width:<?= max(2, $pct) ?>%"></div>
                </div>
                <div class="row mt-2 text-center">
                    <div class="col"><small class="text-muted">≥85% = Unggul</small></div>
                    <div class="col"><small class="text-muted">≥70% = Baik Sekali</small></div>
                    <div class="col"><small class="text-muted">≥55% = Baik</small></div>
                    <div class="col"><small class="text-muted">≥40% = Cukup</small></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Kriteria Cards -->
<div class="row g-3">
<?php foreach ($kriteria as $k): ?>
<?php
$skor    = $k['skor_rata'];
$pctIsi  = $k['jml_butir'] > 0 ? round($k['jml_isi'] / $k['jml_butir'] * 100) : 0;
$pctSkor = $skor !== null ? round($skor / 4 * 100) : 0;
$color   = $pctSkor >= 75 ? 'success' : ($pctSkor >= 50 ? 'primary' : ($pctSkor >= 25 ? 'warning' : 'secondary'));
$url     = site_url('penilaian/kriteria/' . $k['id']) . ($taId ? '?ta=' . $taId : '');
?>
<div class="col-md-6 col-xl-4">
    <div class="card h-100 hover-shadow">
        <div class="card-body pb-2">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <span class="badge bg-dark me-1"><?= esc($k['kode']) ?></span>
                    <span class="fw-semibold"><?= esc($k['nama']) ?></span>
                </div>
                <span class="badge bg-secondary"><?= $k['total_bobot'] ?> pts</span>
            </div>

            <!-- Skor rata-rata butir -->
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">Skor rata-rata</small>
                <strong class="text-<?= $color ?>"><?= $skor !== null ? number_format($skor, 2) . ' / 4.00' : '—' ?></strong>
            </div>
            <div class="progress mb-2" style="height:8px">
                <div class="progress-bar bg-<?= $color ?>" style="width:<?= $pctSkor ?>%"></div>
            </div>

            <!-- Progress isi butir -->
            <div class="d-flex justify-content-between mb-1">
                <small class="text-muted">Butir terisi</small>
                <small class="text-muted"><?= $k['jml_isi'] ?>/<?= $k['jml_butir'] ?></small>
            </div>
            <div class="progress mb-3" style="height:4px">
                <div class="progress-bar bg-info" style="width:<?= $pctIsi ?>%"></div>
            </div>
        </div>
        <div class="card-footer bg-transparent pt-0">
            <a href="<?= $url ?>" class="btn btn-sm btn-outline-primary w-100">
                <i class="bi bi-pencil-square"></i> Input Penilaian
            </a>
        </div>
    </div>
</div>
<?php endforeach ?>
</div>

<style>
.hover-shadow { transition: box-shadow .15s; }
.hover-shadow:hover { box-shadow: 0 4px 16px rgba(0,0,0,.12); }
</style>

<?= $this->endSection() ?>
