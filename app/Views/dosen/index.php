<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-people"></i> DTPR — Dosen Tetap Program Studi</h4>
    <?php if (auth()->user()->can('dosen.manage')): ?>
        <div class="d-flex gap-2">
            <a href="<?= site_url('dosen/import') ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Import Excel
            </a>
            <a href="<?= site_url('dosen/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </a>
        </div>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>
<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<?php if ($summary): ?>
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['icon'=>'bi-people-fill',      'color'=>'primary',   'val'=>$summary['jumlah'], 'label'=>'Total DTPR Aktif'],
        ['icon'=>'bi-mortarboard-fill',  'color'=>'success',   'val'=>$summary['s3'],     'label'=>'Bergelar S3',       'pct'=> $summary['jumlah'] ? round($summary['s3']/$summary['jumlah']*100) : 0, 'target'=>'≥ 30%'],
        ['icon'=>'bi-award-fill',        'color'=>'warning',   'val'=>$summary['lk_gb'],  'label'=>'LK / Guru Besar',   'pct'=> $summary['jumlah'] ? round($summary['lk_gb']/$summary['jumlah']*100) : 0, 'target'=>'≥ 30%'],
        ['icon'=>'bi-patch-check-fill',  'color'=>'info',      'val'=>$summary['serdos'], 'label'=>'Bersertifikat Dosen','pct'=> $summary['jumlah'] ? round($summary['serdos']/$summary['jumlah']*100) : 0],
        ['icon'=>'bi-link-45deg',        'color'=>'secondary', 'val'=>$summary['linier'], 'label'=>'DTPR Linier',        'pct'=> $summary['jumlah'] ? round($summary['linier']/$summary['jumlah']*100) : 0],
    ];
    foreach ($cards as $c): ?>
    <div class="col-6 col-md-2">
        <div class="card stat-card border-<?= $c['color'] ?> h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fs-4 fw-bold text-<?= $c['color'] ?>"><?= $c['val'] ?></div>
                        <div class="text-muted small"><?= $c['label'] ?></div>
                    </div>
                    <i class="bi <?= $c['icon'] ?> fs-3 opacity-25 text-<?= $c['color'] ?>"></i>
                </div>
                <?php if (isset($c['pct'])): ?>
                    <div class="mt-2">
                        <div class="progress" style="height:4px">
                            <div class="progress-bar bg-<?= $c['color'] ?>" style="width:<?= min($c['pct'],100) ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mt-1">
                            <span><?= $c['pct'] ?>%</span>
                            <?php if (isset($c['target'])): ?><span class="text-danger"><?= $c['target'] ?></span><?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <div class="col-6 col-md-2 d-flex align-items-center">
        <a href="<?= site_url('dosen/ewmp') ?>" class="btn btn-outline-primary w-100">
            <i class="bi bi-table"></i><br>Input EWMP
        </a>
    </div>
</div>
<?php endif ?>

<?php if (!empty($kepatuhan)): ?>
<?php
$badge = [
    'ok'   => ['success',   'bi-check-circle-fill',       'Memenuhi'],
    'warn' => ['warning',   'bi-exclamation-triangle-fill','Perlu perhatian'],
    'bad'  => ['danger',    'bi-x-circle-fill',           'Belum memenuhi'],
    'info' => ['secondary', 'bi-dash-circle',             'Belum ada data'],
];
?>
<div class="card mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold"><i class="bi bi-clipboard-check"></i> Kepatuhan DTPR terhadap Kaidah LAM</span>
        <?php if ($kepatuhan['memenuhi']): ?>
            <span class="badge bg-success">Syarat wajib terpenuhi</span>
        <?php else: ?>
            <span class="badge bg-danger"><?= $kepatuhan['wajibBad'] ?> syarat wajib belum terpenuhi</span>
        <?php endif ?>
    </div>
    <div class="table-responsive">
        <table class="table table-sm mb-0 align-middle">
            <tbody>
            <?php foreach ($kepatuhan['checks'] as $c): [$col, $ic, $txt] = $badge[$c['status']]; ?>
                <tr>
                    <td style="width:38px" class="text-center"><i class="bi <?= $ic ?> text-<?= $col ?>"></i></td>
                    <td>
                        <?= esc($c['label']) ?>
                        <?php if ($c['wajib']): ?><span class="badge bg-light text-dark border ms-1">wajib</span><?php endif ?>
                        <div class="small text-muted"><?= esc($c['ket']) ?></div>
                    </td>
                    <td class="text-end fw-semibold text-nowrap"><?= esc($c['nilai']) ?></td>
                    <td class="text-end" style="width:130px"><span class="badge bg-<?= $col ?>"><?= $txt ?></span></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer small text-muted">
        Ambang mengikuti kaidah umum LAM INFOKOM. Item "wajib" menentukan kelayakan dasar DTPR; item lain bersifat indikator mutu.
    </div>
</div>
<?php endif ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Daftar DTPR</span>
        <small class="text-muted"><?= count($dosen) ?> dosen</small>
    </div>
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>NIDN</th>
                    <th>Nama Lengkap</th>
                    <?php if ($isSuperadmin): ?><th>Prodi</th><?php endif ?>
                    <th>Pendidikan</th>
                    <th>Jabatan Akademik</th>
                    <th>Linieritas</th>
                    <th>Serdos</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($dosen)): ?>
                <tr><td colspan="10" class="text-center text-muted py-4">Belum ada data dosen.</td></tr>
            <?php endif ?>
            <?php foreach ($dosen as $i => $d): ?>
                <tr class="<?= $d['aktif'] ? '' : 'text-muted opacity-50' ?>">
                    <td><?= $i + 1 ?></td>
                    <td><code><?= esc($d['nidn'] ?? '-') ?></code></td>
                    <td>
                        <a href="<?= site_url("dosen/{$d['id']}") ?>" class="text-decoration-none fw-semibold">
                            <?= esc(trim(($d['gelar_depan'] ? $d['gelar_depan'].' ' : '').$d['nama'].($d['gelar_belakang'] ? ', '.$d['gelar_belakang'] : ''))) ?>
                        </a>
                        <?php if ($d['bidang_keahlian']): ?>
                            <br><small class="text-muted"><?= esc($d['bidang_keahlian']) ?></small>
                        <?php endif ?>
                    </td>
                    <?php if ($isSuperadmin): ?><td><small><?= esc($d['nama_prodi'] ?? '-') ?></small></td><?php endif ?>
                    <td>
                        <?php
                        $pdColors = ['S3'=>'success','S2'=>'primary','S1'=>'secondary'];
                        $pd = $d['pendidikan_terakhir'] ?? '-';
                        $pdColor = $pdColors[$pd] ?? 'light';
                        ?>
                        <span class="badge bg-<?= $pdColor ?>"><?= esc($pd) ?></span>
                    </td>
                    <td><small><?= esc($d['jabatan_akademik'] ?? '-') ?></small></td>
                    <td>
                        <?php if ($d['linieritas'] === 'linier'): ?>
                            <span class="badge bg-success-subtle text-success">Linier</span>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning">Tidak Linier</span>
                        <?php endif ?>
                    </td>
                    <td class="text-center">
                        <?php if ($d['sertifikat_pendidik']): ?>
                            <i class="bi bi-patch-check-fill text-info" title="Bersertifikat Pendidik"></i>
                        <?php else: ?>
                            <i class="bi bi-dash text-muted"></i>
                        <?php endif ?>
                    </td>
                    <td>
                        <?= $d['aktif']
                            ? '<span class="badge bg-success">Aktif</span>'
                            : '<span class="badge bg-secondary">Non-aktif</span>' ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= site_url("dosen/{$d['id']}") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1" title="Detail"><i class="bi bi-eye"></i></a>
                        <?php if (auth()->user()->can('dosen.manage')): ?>
                            <a href="<?= site_url("dosen/{$d['id']}/edit") ?>" class="btn btn-sm btn-outline-primary py-0 px-1" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("dosen/{$d['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Nonaktifkan dosen ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-1" title="Nonaktifkan"><i class="bi bi-person-dash"></i></button>
                            </form>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
