<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-arrow-repeat"></i> Siklus PPEPP</h4>
    <a href="<?= site_url('spmi/ppepp/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Dokumentasikan siklus mutu <strong>PPEPP</strong> (Penetapan → Pelaksanaan → Evaluasi → Pengendalian → Peningkatan) per siklus tahun. Data ini memperkuat narasi LED.</div>

<?php if (empty($grup)): ?>
    <div class="card"><div class="card-body text-center text-muted py-4">Belum ada data siklus PPEPP.</div></div>
<?php else: foreach ($grup as $siklus => $items): ?>
<div class="card mb-3">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-calendar3"></i> Siklus <?= esc($siklus) ?></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-hover mb-0 align-middle">
                <thead class="table-light"><tr><th style="width:130px">Tahap</th><th>Judul</th><th>Isi</th><th>Tindak Lanjut</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($items as $r): ?>
                    <tr>
                        <td><span class="badge bg-primary"><?= esc($tahap[$r['tahap']] ?? $r['tahap']) ?></span></td>
                        <td><?= esc($r['judul']) ?></td>
                        <td><small class="text-muted"><?= esc(mb_strimwidth((string) $r['isi'], 0, 90, '…')) ?></small></td>
                        <td><small class="text-muted"><?= esc(mb_strimwidth((string) $r['tindak_lanjut'], 0, 60, '…')) ?></small></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url("spmi/ppepp/{$r['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("spmi/ppepp/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endforeach; endif ?>

<?= $this->endSection() ?>
