<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Data Mahasiswa</h4>
    <?php if (auth()->user()->can('mahasiswa.manage')): ?>
        <a href="<?= site_url('mahasiswa/create') ?>" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
        </a>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<?php if ($summary): ?>
<!-- KPI Cards -->
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['val'=>$summary['aktif'],  'label'=>'Mahasiswa Aktif',   'color'=>'primary',   'icon'=>'bi-person-fill'],
        ['val'=>$summary['lulus'],  'label'=>'Lulusan',           'color'=>'success',   'icon'=>'bi-mortarboard'],
        ['val'=>$summary['cuti'],   'label'=>'Cuti',              'color'=>'warning',   'icon'=>'bi-pause-circle'],
        ['val'=>$summary['do'],     'label'=>'DO / Keluar',       'color'=>'danger',    'icon'=>'bi-person-dash'],
        ['val'=>$summary['avg_ipk'] ?? '-', 'label'=>'Rata-rata IPK', 'color'=>'info', 'icon'=>'bi-star-fill'],
        ['val'=>($summary['avg_masa_studi'] ? $summary['avg_masa_studi'].' bln' : '-'), 'label'=>'Rata-rata Masa Studi', 'color'=>'secondary', 'icon'=>'bi-clock'],
    ];
    foreach ($cards as $c): ?>
    <div class="col-6 col-md-2">
        <div class="card stat-card border-<?= $c['color'] ?> h-100">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <div class="fs-4 fw-bold text-<?= $c['color'] ?>"><?= $c['val'] ?></div>
                        <div class="text-muted small"><?= $c['label'] ?></div>
                    </div>
                    <i class="bi <?= $c['icon'] ?> fs-3 opacity-25 text-<?= $c['color'] ?>"></i>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<!-- Tabel 2.A.1 — Perkembangan per TA -->
<?php if (!empty($summary['per_ta'])): ?>
<div class="card mb-4">
    <div class="card-header bg-white fw-semibold">
        <i class="bi bi-bar-chart"></i> Perkembangan Mahasiswa per Tahun Masuk (Tabel 2.A.1)
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tahun Masuk</th>
                    <th class="text-center">Total Diterima</th>
                    <th class="text-center">Masih Aktif</th>
                    <th class="text-center">Lulus</th>
                    <th class="text-center">DO</th>
                    <th class="text-center">% Lulus</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($summary['per_ta'] as $ta): ?>
                <tr>
                    <td><?= esc($ta['ta_kode'] ?? '-') ?></td>
                    <td class="text-center"><?= $ta['jumlah'] ?></td>
                    <td class="text-center"><?= $ta['aktif'] ?></td>
                    <td class="text-center"><span class="text-success fw-semibold"><?= $ta['lulus'] ?></span></td>
                    <td class="text-center"><span class="text-danger"><?= $ta['do_mhs'] ?></span></td>
                    <td class="text-center">
                        <?php $pct = $ta['jumlah'] > 0 ? round($ta['lulus']/$ta['jumlah']*100) : 0; ?>
                        <div class="progress" style="height:6px; min-width:60px">
                            <div class="progress-bar bg-success" style="width:<?= $pct ?>%"></div>
                        </div>
                        <small><?= $pct ?>%</small>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>
<?php endif ?>

<!-- Alert lulusan belum tracer -->
<?php if (!empty($belumTracer)): ?>
<div class="alert alert-warning d-flex justify-content-between align-items-center mb-3">
    <span><i class="bi bi-exclamation-triangle-fill"></i> <strong><?= count($belumTracer) ?> lulusan</strong> belum mengisi Tracer Study.</span>
    <a href="<?= site_url('mahasiswa/tracer') ?>" class="btn btn-sm btn-warning">Isi Tracer Study</a>
</div>
<?php endif ?>

<!-- Filter -->
<form method="get" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
    <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <?php foreach (['aktif','lulus','cuti','do','keluar'] as $s): ?>
            <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach ?>
    </select>
    <select name="ta_id" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
        <option value="">Semua Tahun Masuk</option>
        <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>" <?= $taFilter == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
        <?php endforeach ?>
    </select>
    <?php if ($statusFilter || $taFilter): ?>
        <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    <?php endif ?>
    <span class="text-muted small ms-auto"><?= count($mahasiswa) ?> mahasiswa</span>
</form>

<!-- Tabel Mahasiswa -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover table-sm mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tahun Masuk</th>
                    <th>Status</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Masa Studi</th>
                    <th class="text-center">Tracer</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($mahasiswa)): ?>
                <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data mahasiswa.</td></tr>
            <?php endif ?>
            <?php foreach ($mahasiswa as $i => $m): ?>
                <?php
                $statusColor = match($m['status']) {
                    'aktif'  => 'success',
                    'lulus'  => 'primary',
                    'cuti'   => 'warning',
                    'do'     => 'danger',
                    default  => 'secondary',
                };
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><code><?= esc($m['nim']) ?></code></td>
                    <td>
                        <a href="<?= site_url("mahasiswa/{$m['id']}") ?>" class="text-decoration-none fw-semibold">
                            <?= esc($m['nama']) ?>
                        </a>
                        <?php if ($m['jenis_kelamin']): ?>
                            <small class="text-muted">(<?= $m['jenis_kelamin'] ?>)</small>
                        <?php endif ?>
                    </td>
                    <td><?= esc($m['ta_masuk'] ?? '-') ?></td>
                    <td><span class="badge bg-<?= $statusColor ?>"><?= ucfirst($m['status']) ?></span></td>
                    <td class="text-center"><?= $m['ipk'] ? number_format($m['ipk'],2) : '-' ?></td>
                    <td class="text-center"><?= $m['masa_studi_bulan'] ? $m['masa_studi_bulan'].' bln' : '-' ?></td>
                    <td class="text-center">
                        <?php if ($m['status'] === 'lulus'): ?>
                            <?php
                            $hasTracer = !in_array($m['id'], array_column($belumTracer, 'id'));
                            ?>
                            <?= $hasTracer
                                ? '<i class="bi bi-check-circle-fill text-success" title="Sudah isi tracer"></i>'
                                : '<a href="'.site_url("mahasiswa/tracer/{$m['id']}").'" class="badge bg-warning text-dark">Isi</a>' ?>
                        <?php else: ?>
                            <span class="text-muted">—</span>
                        <?php endif ?>
                    </td>
                    <td class="text-end">
                        <a href="<?= site_url("mahasiswa/{$m['id']}") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-eye"></i></a>
                        <?php if (auth()->user()->can('mahasiswa.manage')): ?>
                            <a href="<?= site_url("mahasiswa/{$m['id']}/edit") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
