<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Publikasi Saya</h4>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= site_url('akun/publikasi/cari') ?>" class="btn btn-info btn-sm"><i class="bi bi-search"></i> Cari &amp; Impor</a>
        <a href="<?= site_url('akun/publikasi/baru') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah</a>
    </div>
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
                <thead class="table-light">
                    <tr><th>#</th><th>Judul</th><th>Jenis</th><th>Media / Tahun</th><th class="text-center">Sitasi</th><th></th></tr>
                </thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada publikasi. Klik "Cari &amp; Impor" untuk menarik dari OpenAlex/Crossref, atau "Tambah" manual.</td></tr>
                <?php else: foreach ($list as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= esc($p['judul']) ?>
                            <?php if ($p['doi']): ?><br><small><a href="https://doi.org/<?= esc($p['doi']) ?>" target="_blank" rel="noopener"><?= esc($p['doi']) ?></a></small><?php endif ?>
                        </td>
                        <td><small><?= esc($jenis[$p['jenis']] ?? $p['jenis']) ?></small></td>
                        <td><small><?= esc($p['nama_media'] ?? '') ?><?= $p['tahun'] ? ' (' . $p['tahun'] . ')' : '' ?></small></td>
                        <td class="text-center"><?= $p['sitasi'] ?: '-' ?></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url("akun/publikasi/{$p['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                            <form method="post" action="<?= site_url("akun/publikasi/{$p['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus publikasi ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($unclaimed)): ?>
<div class="card mt-4 border-warning">
    <div class="card-header bg-warning-subtle fw-semibold">
        <i class="bi bi-inbox"></i> Publikasi Prodi Belum Bertuan (<?= count($unclaimed) ?>)
    </div>
    <div class="card-body">
        <p class="small text-muted mb-3">Publikasi berikut sudah ada di data prodi (mis. diimpor oleh admin) tetapi belum ditautkan ke dosen mana pun. Klik <strong>Klaim</strong> pada yang merupakan karya Anda agar muncul di daftar publikasi Anda.</p>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>#</th><th>Judul</th><th>Penulis</th><th>Media / Tahun</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($unclaimed as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= esc($p['judul']) ?>
                            <?php if ($p['doi']): ?><br><small><a href="https://doi.org/<?= esc($p['doi']) ?>" target="_blank" rel="noopener"><?= esc($p['doi']) ?></a></small><?php endif ?>
                        </td>
                        <td><small><?= esc($p['penulis'] ?? '') ?></small></td>
                        <td><small><?= esc($p['nama_media'] ?? '') ?><?= $p['tahun'] ? ' (' . $p['tahun'] . ')' : '' ?></small></td>
                        <td class="text-end text-nowrap">
                            <form method="post" action="<?= site_url("akun/publikasi/{$p['id']}/klaim") ?>" class="d-inline" onsubmit="return confirm('Klaim publikasi ini sebagai milik Anda?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-success py-0 px-2"><i class="bi bi-hand-index"></i> Klaim</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif ?>

<?= $this->endSection() ?>
