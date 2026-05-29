<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$FASE_LABEL = [
    'penetapan'    => ['Penetapan',    'bi-flag',           'primary'],
    'pelaksanaan'  => ['Pelaksanaan',  'bi-play-circle',    'success'],
    'evaluasi'     => ['Evaluasi',     'bi-clipboard-data', 'info'],
    'pengendalian' => ['Pengendalian', 'bi-sliders',        'warning'],
    'peningkatan'  => ['Peningkatan',  'bi-graph-up-arrow', 'danger'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-clipboard2-check"></i> Isi PPEPP &mdash; <small class="text-muted"><?= esc($butir['butir_kode']) ?></small></h4>
    <a href="<?= site_url('eviden/ppepp') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Daftar Butir</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card mb-3">
    <div class="card-body py-2 small">
        <div><span class="badge bg-secondary"><?= esc($butir['kriteria_kode']) ?></span> <strong><?= esc($butir['kriteria_nama']) ?></strong> &middot; <span class="text-muted"><?= esc($butir['instrumen_nama']) ?></span></div>
        <div class="mt-1"><strong>Butir:</strong> <?= esc($butir['judul']) ?></div>
        <?php if (!empty($butir['deskripsi'])): ?><div class="text-muted mt-1"><?= nl2br(esc($butir['deskripsi'])) ?></div><?php endif ?>
    </div>
</div>

<?php $sources = (new \App\Services\EvidenAutoTarik())->sources(); ?>
<div class="card mb-3">
    <div class="card-header bg-light fw-semibold d-flex align-items-center justify-content-between">
        <span><i class="bi bi-magic"></i> Tarik Eviden Otomatis dari Data Sistem</span>
        <form method="post" action="<?= site_url("eviden/ppepp/{$butir['id']}/tarik-semua") ?>" class="d-inline" onsubmit="return confirm('Tarik bukti dari semua sumber sekaligus?')">
            <?= csrf_field() ?>
            <button class="btn btn-sm btn-primary"><i class="bi bi-lightning-charge"></i> Tarik Semua</button>
        </form>
    </div>
    <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-1">
            <?php foreach ($sources as $key => $info): ?>
            <form method="post" action="<?= site_url("eviden/ppepp/{$butir['id']}/tarik/{$key}") ?>" class="d-inline">
                <?= csrf_field() ?>
                <button class="btn btn-sm btn-outline-primary" title="Tarik ke <?= esc(ucfirst($info['fase'])) ?>">
                    <i class="bi <?= esc($info['icon']) ?>"></i> <?= esc($info['label']) ?>
                </button>
            </form>
            <?php endforeach ?>
        </div>
        <div class="form-text mt-2"><i class="bi bi-info-circle"></i> Hanya data yang memiliki URL bukti (link_bukti / url) yang ditarik. Duplikat di-skip otomatis. Surat Tugas pakai URL validasi publik.</div>
    </div>
</div>

<form method="post" action="<?= site_url("eviden/ppepp/{$butir['id']}/save") ?>" class="mb-3">
    <?= csrf_field() ?>
    <div class="row g-3">
    <?php foreach ($FASE_LABEL as $f => [$lbl, $icon, $col]): ?>
        <div class="col-md-12" id="fase-<?= $f ?>">
            <div class="card border-<?= $col ?>">
                <div class="card-header bg-<?= $col ?> text-white">
                    <span><i class="bi <?= $icon ?>"></i> <strong><?= $lbl ?></strong></span>
                </div>
                <div class="card-body">
                    <label class="form-label small fw-semibold mb-1">Narasi</label>
                    <textarea name="<?= $f ?>" class="form-control form-control-sm" rows="3" placeholder="Tuliskan ringkas <?= strtolower($lbl) ?> untuk butir ini..."><?= esc($eviden[$f] ?? '') ?></textarea>

                    <div class="mt-3">
                        <div class="small fw-semibold mb-1"><i class="bi bi-paperclip"></i> Daftar Bukti (<?= count($bukti[$f]) ?>)</div>
                        <?php if (empty($bukti[$f])): ?>
                            <div class="text-muted small fst-italic">Belum ada bukti.</div>
                        <?php else: ?>
                            <ul class="list-group list-group-flush small">
                                <?php foreach ($bukti[$f] as $bk): ?>
                                <li class="list-group-item d-flex align-items-center justify-content-between gap-2 py-1">
                                    <span>
                                        <a href="<?= esc($bk['url'], 'attr') ?>" target="_blank"><?= esc($bk['label']) ?></a>
                                        <?php if ($bk['sumber'] !== 'manual'): ?>
                                        <span class="badge bg-info" style="font-size:9px"><?= esc($bk['sumber']) ?></span>
                                        <?php endif ?>
                                    </span>
                                    <form method="post" action="<?= site_url("eviden/ppepp/bukti/{$bk['id']}/delete") ?>" onsubmit="return confirm('Hapus bukti ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-x"></i></button>
                                    </form>
                                </li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </div>

                    <div class="mt-2 border-top pt-2">
                        <form method="post" action="<?= site_url("eviden/ppepp/{$butir['id']}/bukti") ?>" class="row g-1 small">
                            <?= csrf_field() ?>
                            <input type="hidden" name="fase" value="<?= $f ?>">
                            <div class="col-md-5"><input type="text" name="label" class="form-control form-control-sm" placeholder="Label bukti" required></div>
                            <div class="col-md-6"><input type="url" name="url" class="form-control form-control-sm" placeholder="https://..." required></div>
                            <div class="col-md-1 d-grid"><button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i></button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach ?>
    </div>

    <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check"></i> Simpan Narasi PPEPP</button></div>
</form>

<?= $this->endSection() ?>
