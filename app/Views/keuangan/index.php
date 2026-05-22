<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-cash-coin"></i> Keuangan (Sumber & Penggunaan Dana)</h4>
    <a href="<?= site_url('keuangan/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr>
                    <th>#</th><th>Arah</th><th>Sumber / Pos</th><th>Kategori</th><th>Tahun Akademik</th><th class="text-end">Jumlah (Rp)</th><th></th>
                </tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data keuangan.</td></tr>
                <?php else: ?>
                <?php foreach ($list as $i => $k): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><span class="badge bg-<?= $k['arah']==='pemasukan'?'success':'danger' ?>"><?= ucfirst($k['arah']) ?></span></td>
                        <td><?= esc($k['sumber_atau_pos']) ?><?php if ($k['keterangan']): ?><br><small class="text-muted"><?= esc($k['keterangan']) ?></small><?php endif ?></td>
                        <td><small><?= esc(str_replace('_',' ', ucfirst($k['kategori'] ?? '-'))) ?></small></td>
                        <td><?= esc($k['ta_kode'] ?? '-') ?></td>
                        <td class="text-end"><?= number_format((int)$k['jumlah'], 0, ',', '.') ?></td>
                        <td>
                            <a href="<?= site_url("keuangan/{$k['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("keuangan/{$k['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
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
    <div class="card-footer small text-muted">Pemasukan mengisi LKPS 1.A.2 (Sumber Pendanaan); Pengeluaran mengisi 1.A.3 (Penggunaan Dana).</div>
</div>

<?= $this->endSection() ?>
