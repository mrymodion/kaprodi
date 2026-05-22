<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Tracer Study</h4>
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Mahasiswa</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>
<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>
<?php if (!($fonnteEnabled ?? false)): ?>
    <div class="alert alert-secondary py-2 small"><i class="bi bi-whatsapp"></i>
        Pengiriman kuesioner via WhatsApp belum aktif. Set <code>fonnte.token</code> di <code>.env</code> untuk mengaktifkan blast tracer study.
    </div>
<?php endif ?>

<!-- KPI Tracer -->
<?php if ($summary): ?>
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['val'=>$summary['total'],                  'label'=>'Total Responden',       'color'=>'primary',   'icon'=>'bi-people-fill'],
        ['val'=>($summary['avg_masa_tunggu'] ?? '-').' bln', 'label'=>'Rata-rata Masa Tunggu', 'color'=>'info','icon'=>'bi-hourglass-split',
         'target'=>'< 3 bln = skor 4', 'ok'=> $summary['avg_masa_tunggu'] !== null && $summary['avg_masa_tunggu'] < 3],
        ['val'=>$summary['pct_sesuai'].'%',          'label'=>'Kesesuaian Bidang Kerja','color'=>'success',  'icon'=>'bi-briefcase-fill',
         'target'=>'≥ 80% = skor 4',   'ok'=> $summary['pct_sesuai'] >= 80],
        ['val'=>$summary['pct_multinasional'].'%',   'label'=>'Multinasional/Internasional','color'=>'warning','icon'=>'bi-globe',
         'target'=>'≥ 20% = skor 4',  'ok'=> $summary['pct_multinasional'] >= 20],
        ['val'=>$summary['pct_wirausaha'].'%',        'label'=>'Wirausaha',             'color'=>'secondary', 'icon'=>'bi-shop'],
        ['val'=>$summary['pct_lanjut_studi'].'%',    'label'=>'Lanjut Studi',          'color'=>'dark',      'icon'=>'bi-mortarboard'],
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
                <?php if (isset($c['target'])): ?>
                    <div class="mt-1">
                        <small class="<?= ($c['ok'] ?? false) ? 'text-success' : 'text-danger' ?>">
                            <i class="bi <?= ($c['ok'] ?? false) ? 'bi-check-circle' : 'bi-x-circle' ?>"></i>
                            <?= $c['target'] ?>
                        </small>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>

<!-- Lulusan belum tracer -->
<?php if (!empty($belum)): ?>
<div class="card border-warning mb-3">
    <div class="card-header bg-warning-subtle fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-exclamation-triangle text-warning"></i> Lulusan Belum Mengisi Tracer (<?= count($belum) ?>)</span>
        <?php if ($fonnteEnabled ?? false): ?>
        <form method="post" action="<?= site_url('mahasiswa/tracer/kirim-semua') ?>" class="m-0"
              onsubmit="return confirm('Kirim link kuesioner via WhatsApp ke semua lulusan yang belum mengisi dan memiliki nomor WA?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> Kirim WA ke Semua</button>
        </form>
        <?php endif ?>
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($belum as $b): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span>
                    <code><?= esc($b['nim']) ?></code>
                    <strong class="ms-2"><?= esc($b['nama']) ?></strong>
                    <?= $b['tgl_lulus'] ? '<small class="text-muted ms-1">lulus '.date('M Y', strtotime($b['tgl_lulus'])).'</small>' : '' ?>
                    <?php if (empty($b['no_hp'])): ?><span class="badge bg-light text-muted border ms-1">tanpa no. WA</span><?php endif ?>
                </span>
                <span class="d-flex gap-1">
                    <?php if (($fonnteEnabled ?? false) && !empty($b['no_hp'])): ?>
                    <form method="post" action="<?= site_url("mahasiswa/tracer/{$b['id']}/kirim") ?>" class="m-0">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-success" title="Kirim link via WhatsApp"><i class="bi bi-whatsapp"></i></button>
                    </form>
                    <?php endif ?>
                    <a href="<?= site_url("mahasiswa/tracer/{$b['id']}") ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-plus-circle"></i> Isi Tracer
                    </a>
                </span>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<!-- Tabel Tracer (Tabel 2.B.4) -->
<div class="card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span>Data Tracer Study — Tabel 2.B.4</span>
        <small class="text-muted"><?= count($tracer) ?> responden</small>
    </div>
    <?php if (empty($tracer)): ?>
        <div class="card-body text-center text-muted py-4">Belum ada data tracer study yang masuk.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Tgl Lulus</th>
                    <th class="text-center">IPK</th>
                    <th class="text-center">Masa Tunggu</th>
                    <th>Kesesuaian</th>
                    <th>Tingkat</th>
                    <th>Perusahaan / Jabatan</th>
                    <th class="text-center">Lanjut Studi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($tracer as $i => $t): ?>
                <?php
                $kes = $t['kesesuaian_bidang_kerja'] ?? '-';
                $kesColor = match($kes) {
                    'sangat_sesuai'=>'success','sesuai'=>'primary','kurang_sesuai'=>'warning','tidak_sesuai'=>'danger',default=>'secondary'
                };
                ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><code><?= esc($t['nim']) ?></code></td>
                    <td><?= esc($t['nama_mhs']) ?></td>
                    <td><?= $t['tgl_lulus'] ? date('M Y', strtotime($t['tgl_lulus'])) : '-' ?></td>
                    <td class="text-center"><?= $t['ipk'] ? number_format($t['ipk'],2) : '-' ?></td>
                    <td class="text-center">
                        <?php if ($t['masa_tunggu_bulan'] !== null): ?>
                            <span class="badge <?= $t['masa_tunggu_bulan'] < 3 ? 'bg-success' : ($t['masa_tunggu_bulan'] < 6 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                                <?= $t['masa_tunggu_bulan'] ?> bln
                            </span>
                        <?php else: ?>-<?php endif ?>
                    </td>
                    <td><span class="badge bg-<?= $kesColor ?>"><?= esc(str_replace('_',' ', ucfirst($kes))) ?></span></td>
                    <td><small><?= esc(ucfirst($t['tingkat_pekerjaan'] ?? '-')) ?></small></td>
                    <td>
                        <small><?= esc($t['nama_perusahaan'] ?? '-') ?></small>
                        <?php if ($t['jabatan']): ?><br><small class="text-muted"><?= esc($t['jabatan']) ?></small><?php endif ?>
                    </td>
                    <td class="text-center"><?= $t['lanjut_studi'] ? '<i class="bi bi-check-circle-fill text-success"></i>' : '-' ?></td>
                    <td>
                        <a href="<?= site_url("mahasiswa/tracer/{$t['mahasiswa_id']}") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
