<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$canGenerate = auth()->user()->can('lkps.generate');
$kriteriaColors = ['C1'=>'success','C2'=>'primary','C3'=>'info','C4'=>'warning','C5'=>'secondary','C6'=>'dark'];

// Group tabel by kriteria_kode
$byKriteria = [];
foreach ($tabelList as $t) $byKriteria[$t['kriteria_kode'] ?: 'lainnya'][] = $t;
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-file-earmark-spreadsheet"></i> Generator LKPS</h4>
        <?php if ($instrumen): ?>
        <small class="text-muted"><?= esc($instrumen['nama']) ?></small>
        <?php endif ?>
    </div>
    <?php if ($canGenerate && $instrumen): ?>
    <form method="post" action="<?= site_url('lkps/generate-all') ?>" class="d-flex gap-2 align-items-center">
        <?= csrf_field() ?>
        <select name="tahun_akademik_id" class="form-select form-select-sm" style="width:160px">
            <option value="">— Semua TS —</option>
            <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-success btn-sm">
            <i class="bi bi-download"></i> Generate Workbook Lengkap
        </button>
    </form>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?= esc(session('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php if (!$instrumen): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Prodi belum memiliki instrumen LAM yang aktif.</div>
<?= $this->endSection() ?>
<?php return ?>
<?php endif ?>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-2">
                <strong><i class="bi bi-list-check"></i> Daftar Tabel LKPS</strong>
                <span class="badge bg-secondary ms-1"><?= count($tabelList) ?> tabel</span>
            </div>
            <div class="card-body p-0">
                <?php foreach ($byKriteria as $kriteria => $tabels): ?>
                <div class="px-3 pt-3 pb-1 bg-light border-bottom">
                    <span class="badge bg-<?= $kriteriaColors[$kriteria] ?? 'secondary' ?>"><?= esc($kriteria) ?></span>
                    <strong class="ms-1"><?= count($tabels) ?> tabel</strong>
                </div>
                <table class="table table-sm table-hover mb-0">
                    <tbody>
                    <?php foreach ($tabels as $t): ?>
                    <tr>
                        <td style="width:80px"><code><?= esc($t['kode']) ?></code></td>
                        <td><?= esc($t['judul']) ?></td>
                        <?php if ($canGenerate): ?>
                        <td class="text-end" style="width:120px">
                            <form method="post" action="<?= site_url('lkps/generate/' . $t['kode']) ?>" class="d-inline">
                                <?= csrf_field() ?>
                                <?php if ($taId): ?><input type="hidden" name="tahun_akademik_id" value="<?= $taId ?>"><?php endif ?>
                                <button type="submit" class="btn btn-xs btn-outline-success py-0 px-2" title="Download tabel ini saja">
                                    <i class="bi bi-download"></i> Excel
                                </button>
                            </form>
                        </td>
                        <?php endif ?>
                    </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
                <?php endforeach ?>
                <?php if (empty($tabelList)): ?>
                <div class="text-center text-muted p-4">Belum ada definisi tabel LKPS.</div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-2"><strong><i class="bi bi-clock-history"></i> File LKPS Terbaru</strong></div>
            <div class="card-body p-0">
                <?php if (empty($existingFiles)): ?>
                <div class="text-muted text-center p-3 small">Belum ada file ter-generate.</div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($existingFiles as $f): ?>
                    <li class="list-group-item p-2 small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-truncate" style="max-width:220px" title="<?= esc($f['name']) ?>">
                                <i class="bi bi-file-earmark-spreadsheet text-success"></i>
                                <?= esc($f['name']) ?>
                            </div>
                            <a href="<?= site_url('lkps/download/' . urlencode($f['name'])) ?>" class="btn btn-xs btn-outline-primary py-0 px-1">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                        <div class="text-muted" style="font-size:.72rem">
                            <?= number_format($f['size'] / 1024, 1) ?> KB &middot;
                            <?= date('d-m-Y H:i', $f['mtime']) ?>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header py-2"><strong><i class="bi bi-info-circle"></i> Catatan</strong></div>
            <div class="card-body small">
                <ul class="mb-0 ps-3">
                    <li>Workbook Lengkap berisi semua tabel + cover.</li>
                    <li>Tabel yang belum memiliki implementasi di driver akan menghasilkan sheet kosong dengan header default.</li>
                    <li>Untuk hasil terbaik, jalankan <em>Auto-Skor</em> di menu Penilaian terlebih dahulu.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
