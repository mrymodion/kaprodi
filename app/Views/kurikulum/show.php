<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
use App\Models\CplModel;
$canManage = auth()->user()->can('kurikulum.manage');
$aspekColors = ['sikap'=>'success','pengetahuan'=>'primary','keterampilan_umum'=>'info','keterampilan_khusus'=>'warning'];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-book"></i> <?= esc($kuri['kode']) ?> — <?= esc($kuri['nama']) ?></h4>
        <small class="text-muted">Berlaku sejak <?= esc($kuri['tahun_berlaku']) ?> &middot; <?= $kuri['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Non-aktif</span>' ?></small>
    </div>
    <a href="<?= site_url('kurikulum') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<!-- Stat bar -->
<div class="row g-3 mb-3">
    <?php foreach ([
        ['val'=>count($cpl),          'label'=>'CPL',          'color'=>'success'],
        ['val'=>count($mk),            'label'=>'Mata Kuliah',  'color'=>'primary'],
        ['val'=>$totSks['total']??0,   'label'=>'Total SKS',    'color'=>'info'],
        ['val'=>$totSks['wajib']??0,   'label'=>'SKS Wajib',   'color'=>'secondary'],
        ['val'=>$totSks['pilihan']??0, 'label'=>'SKS Pilihan', 'color'=>'warning'],
        ['val'=>$totSks['mbkm']??0,    'label'=>'SKS MBKM',   'color'=>'dark'],
    ] as $c): ?>
    <div class="col-4 col-md-2">
        <div class="card text-center border-<?= $c['color'] ?>">
            <div class="card-body p-2">
                <div class="fs-4 fw-bold text-<?= $c['color'] ?>"><?= $c['val'] ?></div>
                <div class="small text-muted"><?= $c['label'] ?></div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<!-- Tabs -->
<?php $activeTab = service('request')->getGet('tab') ?? 'cpl'; ?>
<ul class="nav nav-tabs mb-3" id="kuriTab">
    <li class="nav-item"><a class="nav-link" href="#tab-cpl"  data-bs-toggle="tab">CPL <span class="badge bg-success"><?= count($cpl) ?></span></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-mk"   data-bs-toggle="tab">Mata Kuliah <span class="badge bg-primary"><?= count($mk) ?></span></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-peta" data-bs-toggle="tab"><i class="bi bi-grid-3x3"></i> Peta CPL-MK</a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-ket"  data-bs-toggle="tab"><i class="bi bi-bar-chart"></i> Ketercapaian CPL</a></li>
</ul>

<div class="tab-content">

    <!-- ── TAB CPL ─────────────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-cpl">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold">Capaian Pembelajaran Lulusan (CPL)</span>
            <?php if ($canManage): ?>
                <span class="d-flex gap-2">
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/cpl/import") ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-file-earmark-excel"></i> Import
                    </a>
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/cpl/create") ?>" class="btn btn-sm btn-success">
                        <i class="bi bi-plus"></i> Tambah CPL
                    </a>
                </span>
            <?php endif ?>
        </div>

        <?php
        $aspekGroups = [];
        foreach ($cpl as $c) { $aspekGroups[$c['aspek']][] = $c; }
        foreach (['sikap','pengetahuan','keterampilan_umum','keterampilan_khusus'] as $aspek):
            if (empty($aspekGroups[$aspek])) continue;
            $color = $aspekColors[$aspek];
        ?>
        <div class="card mb-3">
            <div class="card-header bg-<?= $color ?> bg-opacity-10 border-<?= $color ?>">
                <span class="fw-semibold text-<?= $color ?>"><?= CplModel::aspekLabel($aspek) ?></span>
                <span class="badge bg-<?= $color ?> ms-1"><?= count($aspekGroups[$aspek]) ?></span>
            </div>
            <div class="list-group list-group-flush">
            <?php foreach ($aspekGroups[$aspek] as $c): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start gap-2">
                    <span class="badge bg-<?= $color ?> mt-1" style="min-width:50px;text-align:center"><?= esc($c['kode']) ?></span>
                    <span class="flex-grow-1 small"><?= esc($c['deskripsi']) ?></span>
                    <?php if ($canManage): ?>
                    <div class="d-flex gap-1 flex-shrink-0">
                        <a href="<?= site_url("kurikulum/{$kuri['id']}/cpl/{$c['id']}/edit") ?>" class="btn btn-xs btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a>
                        <form method="post" action="<?= site_url("kurikulum/{$kuri['id']}/cpl/{$c['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus CPL ini?')">
                            <?= csrf_field() ?>
                            <button class="btn btn-xs btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                    <?php endif ?>
                </div>
            <?php endforeach ?>
            </div>
        </div>
        <?php endforeach ?>
        <?php if (empty($cpl)): ?>
            <div class="alert alert-warning">Belum ada CPL. <a href="<?= site_url("kurikulum/{$kuri['id']}/cpl/create") ?>">Tambah sekarang</a>.</div>
        <?php endif ?>
    </div>

    <!-- ── TAB MK ──────────────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-mk">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold">Daftar Mata Kuliah — Tabel 2.B.1</span>
            <?php if ($canManage): ?>
                <span class="d-flex gap-2">
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/mk/import") ?>" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-file-earmark-excel"></i> Import
                    </a>
                    <a href="<?= site_url("kurikulum/{$kuri['id']}/mk/create") ?>" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus"></i> Tambah MK
                    </a>
                </span>
            <?php endif ?>
        </div>
        <?php
        $semGroups = [];
        foreach ($mk as $m) { $semGroups[$m['semester']][] = $m; }
        ksort($semGroups);
        foreach ($semGroups as $sem => $mkList):
        ?>
        <div class="card mb-3">
            <div class="card-header bg-light fw-semibold">Semester <?= $sem ?></div>
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th><th>Nama Mata Kuliah</th>
                            <th class="text-center">SKS T</th><th class="text-center">SKS P</th><th class="text-center">SKS L</th>
                            <th class="text-center">Total</th><th>Jenis</th><th>CPL Terkait</th>
                            <?php if ($canManage): ?><th></th><?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mkList as $m): ?>
                        <tr>
                            <td><code><?= esc($m['kode']) ?></code></td>
                            <td><?= esc($m['nama']) ?></td>
                            <td class="text-center"><?= $m['sks_teori'] ?></td>
                            <td class="text-center"><?= $m['sks_praktek'] ?></td>
                            <td class="text-center"><?= $m['sks_lapangan'] ?></td>
                            <td class="text-center"><strong><?= $m['total_sks'] ?></strong></td>
                            <td><span class="badge bg-<?= ['wajib'=>'primary','pilihan'=>'info','mbkm'=>'warning'][$m['jenis']] ?? 'secondary' ?>"><?= ucfirst($m['jenis']) ?></span></td>
                            <td>
                                <?php if ($m['cpl_terkait']): ?>
                                    <small class="text-muted"><?= esc($m['cpl_terkait']) ?></small>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger">Belum dipeta</span>
                                <?php endif ?>
                            </td>
                            <?php if ($canManage): ?>
                            <td class="text-end">
                                <a href="<?= site_url("kurikulum/{$kuri['id']}/mk/{$m['id']}/edit") ?>" class="btn btn-xs btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="<?= site_url("kurikulum/{$kuri['id']}/mk/{$m['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus MK ini?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-xs btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                            <?php endif ?>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach ?>
        <?php if (empty($mk)): ?>
            <div class="alert alert-warning">Belum ada mata kuliah.</div>
        <?php endif ?>
    </div>

    <!-- ── TAB PETA CPL×MK ─────────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-peta">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold">Peta CPL × Mata Kuliah — Tabel 2.B.3</span>
            <?php if ($canManage && !empty($cpl) && !empty($mk)): ?>
                <button class="btn btn-sm btn-success" onclick="document.getElementById('petaForm').submit()">
                    <i class="bi bi-floppy"></i> Simpan Peta
                </button>
            <?php endif ?>
        </div>

        <?php if (empty($cpl) || empty($mk)): ?>
            <div class="alert alert-warning">Lengkapi CPL dan Mata Kuliah terlebih dahulu.</div>
        <?php else: ?>
        <form id="petaForm" method="post" action="<?= site_url("kurikulum/{$kuri['id']}/peta/save") ?>">
            <?= csrf_field() ?>
            <div class="table-responsive">
                <table class="table table-sm table-bordered text-center mb-0" style="min-width:600px">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start" style="min-width:180px">Mata Kuliah</th>
                            <th>Sem</th>
                            <?php foreach ($cpl as $c): ?>
                                <th title="<?= esc($c['deskripsi']) ?>" class="text-<?= $aspekColors[$c['aspek']] ?? 'dark' ?>">
                                    <?= esc($c['kode']) ?>
                                </th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mk as $m): ?>
                        <tr>
                            <td class="text-start">
                                <code><?= esc($m['kode']) ?></code><br>
                                <small><?= esc($m['nama']) ?></small>
                            </td>
                            <td><?= $m['semester'] ?></td>
                            <?php foreach ($cpl as $c): ?>
                                <td>
                                    <input type="checkbox"
                                        name="peta[<?= $m['id'] ?>][]"
                                        value="<?= $c['id'] ?>"
                                        <?= !empty($peta[$m['id']][$c['id']]) ? 'checked' : '' ?>
                                        <?= $canManage ? '' : 'disabled' ?>
                                        class="form-check-input">
                                </td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="mt-2 small text-muted">
            <i class="bi bi-info-circle"></i>
            Setiap CPL harus didukung oleh minimal 1 MK. Warna header CPL:
            <span class="text-success">Sikap</span> · <span class="text-primary">Pengetahuan</span> ·
            <span class="text-info">KU</span> · <span class="text-warning">KK</span>
        </div>
        <?php endif ?>
    </div>

    <!-- ── TAB KETERCAPAIAN CPL ─────────────────────────────────── -->
    <div class="tab-pane fade" id="tab-ket">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="fw-semibold">Ketercapaian CPL per MK — Tabel 2.B.2</span>
            <a href="<?= site_url("kurikulum/{$kuri['id']}/ketercapaian?ta_id={$taId}") ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Input Ketercapaian
            </a>
        </div>

        <!-- Filter TA -->
        <form method="get" class="mb-3">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0">Tahun Akademik:</label>
                <select name="ta_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>>
                            <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <input type="hidden" name="tab" value="ket">
            </div>
        </form>

        <?php if (empty($avgCpl)): ?>
            <div class="alert alert-info">Pilih tahun akademik dan input data ketercapaian.</div>
        <?php else: ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Kode CPL</th><th>Aspek</th><th>Deskripsi</th><th class="text-center">Rata-rata Ketercapaian</th><th class="text-center">Data MK</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($avgCpl as $row):
                        $pct = $row['avg_pct'];
                        $color = $pct === null ? 'secondary' : ($pct >= 75 ? 'success' : ($pct >= 60 ? 'warning' : 'danger'));
                    ?>
                        <tr>
                            <td><span class="badge bg-<?= $aspekColors[$row['aspek']] ?? 'secondary' ?>"><?= esc($row['kode']) ?></span></td>
                            <td><small><?= CplModel::aspekLabel($row['aspek']) ?></small></td>
                            <td><small><?= esc(mb_substr($row['deskripsi'], 0, 80)).'...' ?></small></td>
                            <td class="text-center">
                                <?php if ($pct !== null): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:8px">
                                            <div class="progress-bar bg-<?= $color ?>" style="width:<?= min($pct,100) ?>%"></div>
                                        </div>
                                        <span class="fw-semibold text-<?= $color ?>" style="width:45px"><?= round($pct,1) ?>%</span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Belum ada data</span>
                                <?php endif ?>
                            </td>
                            <td class="text-center"><?= $row['data_pts'] ?> MK</td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted small">Target: ≥ 75% = CPL tercapai (skor 4 LAM)</div>
        </div>
        <?php endif ?>
    </div>
</div>

<script>
// Aktifkan tab dari hash URL
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (hash) {
        const tab = document.querySelector(`a[href="${hash}"]`);
        if (tab) new bootstrap.Tab(tab).show();
    } else {
        new bootstrap.Tab(document.querySelector('#kuriTab a')).show();
    }
    // Simpan hash saat klik tab
    document.querySelectorAll('#kuriTab a').forEach(t => {
        t.addEventListener('shown.bs.tab', e => {
            history.replaceState(null, '', e.target.getAttribute('href'));
        });
    });
});
</script>

<?= $this->endSection() ?>
