<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi <?= esc($def['icon']) ?>"></i> <?= esc($def['title']) ?>
        <small class="text-muted">— LKPS <?= esc($def['lkps']) ?></small></h4>
    <a href="<?= site_url("borang/{$slug}/create") ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr>
                    <th>#</th>
                    <?php foreach ($def['fields'] as $name => $f): ?>
                        <?php if ($name === 'tupoksi' || $name === 'keterangan') continue; ?>
                        <th><?= esc($f[0]) ?></th>
                    <?php endforeach ?>
                    <th></th>
                </tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="20" class="text-center text-muted py-4">Belum ada data.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $row): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <?php foreach ($def['fields'] as $name => $f): ?>
                            <?php if ($name === 'tupoksi' || $name === 'keterangan') continue; ?>
                            <td>
                                <?php if ($f[1] === 'ta'): ?>
                                    <?= esc($row['ta_kode'] ?? '-') ?>
                                <?php elseif ($f[1] === 'dosen'): ?>
                                    <?= !empty($row['dosen_nama']) ? esc($row['dosen_nama']) : '<span class="text-muted">-</span>' ?>
                                <?php elseif ($f[1] === 'select'): ?>
                                    <?= esc($f[3][$row[$name]] ?? ($row[$name] ?? '-')) ?>
                                <?php elseif ($f[1] === 'file'): ?>
                                    <?php $fv = $row[$name] ?? ''; ?>
                                    <?php if (\App\Services\EvidenceStorage::isStoredFile($fv)): ?>
                                        <a href="<?= site_url('eviden/file/' . $fv) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i></a>
                                    <?php elseif (!empty($fv)): ?>
                                        <a href="<?= esc($fv, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i></a>
                                    <?php else: ?>-<?php endif ?>
                                <?php else: ?>
                                    <?= esc($row[$name] ?? '-') ?>
                                <?php endif ?>
                            </td>
                        <?php endforeach ?>
                        <td class="text-nowrap">
                            <a href="<?= site_url("borang/{$slug}/{$row['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("borang/{$slug}/{$row['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
