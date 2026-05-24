<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$canManage = auth()->user()->can('tridharma.manage');
$jenisLabels = \App\Models\PublikasiModel::JENIS_LABELS;
$hkiLabels   = \App\Models\HkiModel::JENIS_LABELS;
$dosenList   = $dosenList ?? [];
$ownerMap    = $ownerMap ?? [];
$dosenNama = [];
foreach ($dosenList as $dd) {
    $dosenNama[(int) $dd['id']] = trim(($dd['gelar_depan'] ? $dd['gelar_depan'] . ' ' : '') . $dd['nama'] . ($dd['gelar_belakang'] ? ', ' . $dd['gelar_belakang'] : ''));
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-text"></i> Publikasi & HKI</h4>
    <?php if ($canManage): ?>
    <div class="d-flex gap-2 flex-wrap">
        <a href="<?= site_url('publikasi/cari') ?>" class="btn btn-info btn-sm"><i class="bi bi-search"></i> Cari Publikasi</a>
        <form method="post" action="<?= site_url('publikasi/sitasi/update') ?>"
              onsubmit="return confirm('Perbarui jumlah sitasi semua publikasi ber-DOI dari OpenAlex?')">
            <?= csrf_field() ?>
            <button class="btn btn-outline-info btn-sm"><i class="bi bi-arrow-repeat"></i> Perbarui Sitasi</button>
        </form>
        <a href="<?= site_url('publikasi/create') ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle"></i> Tambah Publikasi</a>
        <a href="<?= site_url('publikasi/hki/create') ?>" class="btn btn-outline-primary btn-sm"><i class="bi bi-shield-check"></i> Tambah HKI</a>
    </div>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif ?>

<!-- Filter -->
<form class="row g-2 mb-3 align-items-center" method="get">
    <div class="col-auto">
        <select name="kategori" class="form-select form-select-sm">
            <option value="">Semua Kategori</option>
            <option value="penelitian" <?= $kategori==='penelitian'?'selected':'' ?>>Penelitian</option>
            <option value="pkm" <?= $kategori==='pkm'?'selected':'' ?>>PkM</option>
        </select>
    </div>
    <div class="col-auto">
        <div class="form-check form-check-inline mb-0">
            <input class="form-check-input" type="checkbox" name="klaim" value="unclaimed" id="chkUnclaimed" <?= ($klaimFilter ?? false) ? 'checked' : '' ?> onchange="this.form.submit()">
            <label class="form-check-label small" for="chkUnclaimed">
                Hanya belum diklaim
                <?php if (($unclaimedCount ?? 0) > 0): ?>
                    <span class="badge bg-warning text-dark"><?= $unclaimedCount ?></span>
                <?php endif ?>
            </label>
        </div>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-funnel"></i> Filter</button>
        <a href="<?= site_url('publikasi') ?>" class="btn btn-sm btn-outline-secondary">Reset</a>
    </div>
</form>

<!-- Summary Cards -->
<?php if ($pubSum): ?>
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-primary text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-primary"><?= $pubSum['total'] ?></div>
                <div class="small text-muted">Total Publikasi</div>
            </div>
        </div>
    </div>
    <?php
    $intBereputasi = ($pubSum['penelitian']['jurnal_internasional_bereputasi']['jml'] ?? 0) + ($pubSum['pkm']['jurnal_internasional_bereputasi']['jml'] ?? 0);
    $intl = ($pubSum['penelitian']['jurnal_internasional']['jml'] ?? 0) + ($pubSum['pkm']['jurnal_internasional']['jml'] ?? 0) + $intBereputasi;
    $totalHki = $hkiSum['total'] ?? 0;
    ?>
    <div class="col-6 col-md-3">
        <div class="card border-success text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-success"><?= $intl ?></div>
                <div class="small text-muted">Jurnal Internasional</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-warning text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-warning"><?= $intBereputasi ?></div>
                <div class="small text-muted">Bereputasi (Scopus/WoS)</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-info text-center">
            <div class="card-body p-2">
                <div class="fs-3 fw-bold text-info"><?= $totalHki ?></div>
                <div class="small text-muted">HKI</div>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="pubTabs">
    <li class="nav-item"><a class="nav-link" href="#tab-pub" data-bs-toggle="tab">Publikasi <span class="badge bg-primary"><?= count($pubList) ?></span></a></li>
    <li class="nav-item"><a class="nav-link" href="#tab-hki" data-bs-toggle="tab">HKI <span class="badge bg-info"><?= count($hkiList) ?></span></a></li>
</ul>

<div class="tab-content" id="pubTabContent">
    <!-- Publikasi Tab -->
    <div class="tab-pane fade" id="tab-pub">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Media / Tahun</th>
                                <th>Sitasi</th>
                                <th>Dosen Pemilik</th>
                                <?php if ($canManage): ?><th></th><?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($pubList)): ?>
                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data publikasi.</td></tr>
                        <?php else: ?>
                        <?php foreach ($pubList as $i => $p): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td>
                                <?= esc($p['judul']) ?>
                                <?php if ($p['doi']): ?><br><small><a href="https://doi.org/<?= esc($p['doi']) ?>" target="_blank"><?= esc($p['doi']) ?></a></small><?php endif ?>
                            </td>
                            <td><small><?= esc($p['penulis']) ?></small></td>
                            <td><small><?= esc($jenisLabels[$p['jenis']] ?? $p['jenis']) ?></small></td>
                            <td><span class="badge <?= $p['kategori']==='penelitian'?'bg-primary':'bg-success' ?>"><?= $p['kategori'] ?></span></td>
                            <td><small><?= esc($p['nama_media'] ?? '') ?><?= $p['tahun'] ? ' ('.$p['tahun'].')' : '' ?></small></td>
                            <td class="text-center"><?= $p['sitasi'] ?: '-' ?></td>
                            <td style="min-width:160px">
                                <?php $pemilik = $ownerMap[(int) $p['id']] ?? []; ?>
                                <?php if ($pemilik): ?>
                                    <?php foreach ($pemilik as $did): ?>
                                        <span class="badge bg-light text-dark border"><?= esc($dosenNama[$did] ?? ('#' . $did)) ?></span>
                                    <?php endforeach ?>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-dark border">belum bertuan</span>
                                <?php endif ?>
                                <?php if ($canManage): ?>
                                    <a href="<?= site_url("publikasi/{$p['id']}/pemilik") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1 ms-1" title="Atur dosen pemilik"><i class="bi bi-people"></i></a>
                                <?php endif ?>
                            </td>
                            <?php if ($canManage): ?>
                            <td>
                                <a href="<?= site_url("publikasi/{$p['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="<?= site_url("publikasi/{$p['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                            <?php endif ?>
                        </tr>
                        <?php endforeach ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- HKI Tab -->
    <div class="tab-pane fade" id="tab-hki">
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Judul</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>No. HKI</th>
                                <th>Tahun</th>
                                <th>DTPR</th>
                                <th>Status</th>
                                <?php if ($canManage): ?><th></th><?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($hkiList)): ?>
                            <tr><td colspan="9" class="text-center text-muted py-4">Belum ada data HKI.</td></tr>
                        <?php else: ?>
                        <?php foreach ($hkiList as $i => $h): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= esc($h['judul']) ?></td>
                            <td><?= esc($hkiLabels[$h['jenis']] ?? $h['jenis']) ?></td>
                            <td><span class="badge <?= $h['kategori']==='penelitian'?'bg-primary':'bg-success' ?>"><?= $h['kategori'] ?></span></td>
                            <td><?= esc($h['no_hki'] ?? '-') ?></td>
                            <td><?= esc($h['tahun'] ?? '-') ?></td>
                            <td><?php $hd = (int) ($h['dosen_id'] ?? 0); ?>
                                <?php if ($hd && isset($dosenNama[$hd])): ?>
                                    <span class="badge bg-light text-dark border"><?= esc($dosenNama[$hd]) ?></span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif ?>
                            </td>
                            <td><span class="badge <?= $h['status']==='granted'?'bg-success':'bg-warning text-dark' ?>"><?= $h['status'] ?></span></td>
                            <?php if ($canManage): ?>
                            <td>
                                <a href="<?= site_url("publikasi/hki/{$h['id']}/edit") ?>" class="btn btn-sm btn-outline-secondary py-0 px-1"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="<?= site_url("publikasi/hki/{$h['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus?')">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                            <?php endif ?>
                        </tr>
                        <?php endforeach ?>
                        <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var hash = window.location.hash || '#tab-pub';
    var tab = document.querySelector('#pubTabs a[href="' + hash + '"]');
    if (tab) new bootstrap.Tab(tab).show();
    document.querySelectorAll('#pubTabs a[data-bs-toggle="tab"]').forEach(function(el) {
        el.addEventListener('shown.bs.tab', function(e) {
            history.replaceState(null, null, e.target.getAttribute('href'));
        });
    });
    // Activate first tab if no hash
    if (!window.location.hash) {
        var firstTab = document.querySelector('#pubTabs a');
        if (firstTab) new bootstrap.Tab(firstTab).show();
    }
});
</script>

<?= $this->endSection() ?>
