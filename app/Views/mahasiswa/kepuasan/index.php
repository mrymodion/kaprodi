<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-emoji-smile"></i> Survei Kepuasan Pengguna Lulusan <small class="text-muted">— Tabel 2.B.6</small></h4>
    <a href="<?= site_url('mahasiswa/tracer') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Tracer Study</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle"></i> <?= esc(session('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (!($fonnteEnabled ?? false)): ?>
    <div class="alert alert-secondary py-2 small"><i class="bi bi-whatsapp"></i>
        Pengiriman via WhatsApp belum aktif. Set <code>fonnte.token</code> di <code>.env</code>.
    </div>
<?php endif ?>

<?php if (!empty($summary)): ?>
<div class="card mb-3">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span>Rata-rata Kepuasan (skala 1–4)</span>
        <span>Responden: <strong><?= (int) $summary['total'] ?></strong> · Rata-rata keseluruhan:
            <strong class="text-primary"><?= $summary['avg_all'] !== null ? number_format($summary['avg_all'], 2) : '-' ?></strong></span>
    </div>
    <div class="table-responsive">
        <table class="table table-sm mb-0">
            <tbody>
            <?php foreach ($aspek as $k => $label): $v = $summary['avg'][$k] ?? null; ?>
                <tr>
                    <td style="width:55%"><?= esc($label) ?></td>
                    <td>
                        <div class="progress" style="height:18px">
                            <div class="progress-bar bg-<?= ($v ?? 0) >= 3 ? 'success' : (($v ?? 0) >= 2 ? 'warning' : 'danger') ?>"
                                 style="width:<?= $v ? round($v/4*100) : 0 ?>%"><?= $v !== null ? number_format($v,2) : '-' ?></div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>

<?php if (!empty($belum)): ?>
<div class="card border-warning mb-3">
    <div class="card-header bg-warning-subtle fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-exclamation-triangle text-warning"></i> Lulusan Belum Ada Survei Kepuasan (<?= count($belum) ?>)</span>
        <?php if ($fonnteEnabled ?? false): ?>
        <form method="post" action="<?= site_url('mahasiswa/kepuasan/kirim-semua') ?>" class="m-0"
              onsubmit="return confirm('Kirim link survei kepuasan via WhatsApp ke semua alumni yang punya nomor WA (untuk diteruskan ke atasan)?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> Kirim WA ke Semua</button>
        </form>
        <?php endif ?>
    </div>
    <div class="list-group list-group-flush">
        <?php foreach ($belum as $b): ?>
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <span><code><?= esc($b['nim']) ?></code> <strong class="ms-2"><?= esc($b['nama']) ?></strong>
                    <?php if (empty($b['no_hp'])): ?><span class="badge bg-light text-muted border ms-1">tanpa no. WA</span><?php endif ?></span>
                <span class="d-flex gap-1">
                    <?php if (($fonnteEnabled ?? false) && !empty($b['no_hp'])): ?>
                    <form method="post" action="<?= site_url("mahasiswa/kepuasan/{$b['id']}/kirim") ?>" class="m-0">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-success" title="Kirim link via WhatsApp"><i class="bi bi-whatsapp"></i></button>
                    </form>
                    <?php endif ?>
                    <a href="<?= site_url("mahasiswa/kepuasan/{$b['id']}") ?>" class="btn btn-sm btn-warning"><i class="bi bi-plus-circle"></i> Input</a>
                </span>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<div class="card">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between">
        <span>Data Survei Kepuasan</span><small class="text-muted"><?= count($list) ?> responden</small>
    </div>
    <?php if (empty($list)): ?>
        <div class="card-body text-center text-muted py-4">Belum ada data survei kepuasan.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0 align-middle">
            <thead class="table-light"><tr>
                <th>#</th><th>NIM</th><th>Nama</th><th>Perusahaan</th>
                <?php foreach ($aspek as $label): ?><th class="text-center" title="<?= esc($label) ?>"><?= esc(mb_substr($label,0,4)) ?>.</th><?php endforeach ?>
                <th></th>
            </tr></thead>
            <tbody>
            <?php foreach ($list as $i => $r): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><code><?= esc($r['nim']) ?></code></td>
                    <td><?= esc($r['nama_mhs']) ?></td>
                    <td><small><?= esc($r['nama_perusahaan'] ?? '-') ?></small></td>
                    <?php foreach (array_keys($aspek) as $k): ?>
                        <td class="text-center"><?= $r[$k] !== null ? (int)$r[$k] : '-' ?></td>
                    <?php endforeach ?>
                    <td><a href="<?= site_url("mahasiswa/kepuasan/{$r['mahasiswa_id']}") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
