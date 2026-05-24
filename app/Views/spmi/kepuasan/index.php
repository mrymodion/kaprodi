<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-emoji-smile"></i> Rekap Kepuasan Pemangku Kepentingan Internal</h4>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Rekap umpan balik/kepuasan mahasiswa, dosen, dan tenaga kependidikan dari modul Survei — bukti umpan balik pemangku kepentingan untuk SPMI (C1/C5).</div>

<?php foreach ($targetLabel as $key => $label): ?>
<div class="card mb-3">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-people"></i> Kepuasan <?= esc($label) ?></span>
        <?php $items = $grup[$key] ?? []; $totResp = array_sum(array_map(fn($x) => (int) $x['responden'], $items)); ?>
        <span class="badge bg-primary"><?= count($items) ?> survei &middot; <?= $totResp ?> responden</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Judul Survei</th><th>Periode</th><th class="text-center">Responden</th></tr></thead>
                <tbody>
                <?php if (empty($items)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-3">Belum ada survei kepuasan <?= esc(strtolower($label)) ?>.</td></tr>
                <?php else: foreach ($items as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($s['judul']) ?></td>
                        <td><small><?= esc($s['periode'] ?? '-') ?></small></td>
                        <td class="text-center"><span class="badge <?= (int) $s['responden'] > 0 ? 'bg-success' : 'bg-secondary' ?>"><?= (int) $s['responden'] ?></span></td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach ?>

<?= $this->endSection() ?>
