<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Peta Kurikulum <?= esc($kurikulum['kode']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-diagram-3 text-info me-2"></i> 
                Peta CPL - Mata Kuliah
            </h1>
            <p class="text-muted mb-0">Kurikulum: <strong><?= esc($kurikulum['kode']) ?></strong> (Tahun Berlaku: <?= esc($kurikulum['tahun_berlaku']) ?>)</p>
        </div>
        <a href="<?= site_url('kurikulum') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= session('success') ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?></div>
    <?php endif; ?>

    <!-- Tabs Navigasi -->
    <ul class="nav nav-tabs mb-4" id="kurikulumTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold active" id="cpl-tab" data-bs-toggle="tab" data-bs-target="#tab-cpl" type="button" role="tab"><i class="bi bi-award me-1"></i> Daftar CPL</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="mk-tab" data-bs-toggle="tab" data-bs-target="#tab-mk" type="button" role="tab"><i class="bi bi-journal-text me-1"></i> Mata Kuliah</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="peta-tab" data-bs-toggle="tab" data-bs-target="#tab-peta" type="button" role="tab"><i class="bi bi-grid-3x3 me-1"></i> Matriks Pemetaan</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link fw-semibold" id="ket-tab" data-bs-toggle="tab" data-bs-target="#tab-ket" type="button" role="tab"><i class="bi bi-bar-chart me-1"></i> Ketercapaian CPL</button>
        </li>
    </ul>

    <div class="tab-content" id="kurikulumTabsContent">

        <!-- TAB 1: CPL -->
        <div class="tab-pane fade show active" id="tab-cpl" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-dark">Daftar Capaian Pembelajaran Lulusan (CPL)</h5>
                <div class="btn-group shadow-sm">
                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/cpl/create") ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah CPL</a>
                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/cpl/import") ?>" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Import Excel</a>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Kode</th><th>Aspek</th><th>Deskripsi</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            <?php if(empty($cpl)): ?><tr><td colspan="4" class="text-center py-4 text-muted">Belum ada CPL.</td></tr><?php endif; ?>
                            <?php foreach($cpl as $c): ?>
                            <tr>
                                <td class="fw-bold"><?= esc($c['kode']) ?></td>
                                <td><span class="badge bg-secondary"><?= esc(ucfirst(str_replace('_', ' ', $c['aspek']))) ?></span></td>
                                <td><?= esc($c['deskripsi']) ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/cpl/{$c['id']}/edit") ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="<?= site_url("kurikulum/{$kurikulum['id']}/cpl/{$c['id']}/delete") ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus CPL ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 2: MATA KULIAH -->
        <div class="tab-pane fade" id="tab-mk" role="tabpanel">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 text-dark">Daftar Mata Kuliah</h5>
                <div class="btn-group shadow-sm">
                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/mk/create") ?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Tambah MK</a>
                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/mk/import") ?>" class="btn btn-success btn-sm"><i class="bi bi-file-earmark-excel me-1"></i> Import Excel</a>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0 table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light"><tr><th>Smt</th><th>Kode</th><th>Mata Kuliah</th><th>SKS</th><th>Jenis</th><th class="text-end">Aksi</th></tr></thead>
                        <tbody>
                            <?php if(empty($mk)): ?><tr><td colspan="6" class="text-center py-4 text-muted">Belum ada Mata Kuliah.</td></tr><?php endif; ?>
                            <?php foreach($mk as $m): ?>
                            <tr>
                                <td class="text-center"><?= $m['semester'] ?></td>
                                <td class="fw-bold"><?= esc($m['kode']) ?></td>
                                <td><?= esc($m['nama']) ?></td>
                                <td><?= $m['sks'] ?> <span class="text-muted small">(T:<?= $m['sks_teori'] ?> P:<?= $m['sks_praktek'] ?> L:<?= $m['sks_lapangan'] ?>)</span></td>
                                <td><?= esc(ucfirst($m['jenis'])) ?></td>
                                <td class="text-end text-nowrap">
                                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/mk/{$m['id']}/edit") ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="<?= site_url("kurikulum/{$kurikulum['id']}/mk/{$m['id']}/delete") ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus Mata Kuliah ini?')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TAB 3: MATRIKS PEMETAAN -->
        <div class="tab-pane fade" id="tab-peta" role="tabpanel">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-info"><i class="bi bi-grid-3x3 me-1"></i> Matriks Pemetaan (Tabel LKPS 2.B.3)</h6>
                    <button form="form-peta" type="submit" class="btn btn-sm btn-primary shadow-sm"><i class="bi bi-save me-1"></i> Simpan Peta</button>
                </div>
        <div class="card-body p-0">
            <form id="form-peta" action="<?= site_url("kurikulum/{$kurikulum['id']}/peta/save") ?>" method="POST">
                <?= csrf_field() ?>
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle mb-0 text-center" style="font-size: 0.85rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="text-start" style="width: 250px; position: sticky; left: 0; top: 0; background: #f8f9fa; z-index: 3;">Mata Kuliah \ CPL</th>
                                <?php foreach ($cpl as $c): ?>
                                    <th style="position: sticky; top: 0; background: #f8f9fa; z-index: 2;" title="<?= esc($c['deskripsi']) ?>">
                                        <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?= esc($c['deskripsi']) ?>">
                                            <?= esc($c['kode']) ?>
                                        </span>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mk)): ?>
                                <tr>
                                    <td colspan="<?= count($cpl) + 1 ?>" class="text-center py-5 text-muted">Belum ada data Mata Kuliah di kurikulum ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mk as $m): ?>
                                    <tr>
                                        <td class="text-start" style="position: sticky; left: 0; background: #fff; z-index: 1;">
                                            <strong class="d-block text-dark"><?= esc($m['kode']) ?></strong>
                                            <span class="text-muted"><?= esc($m['nama']) ?> (Smt <?= $m['semester'] ?> - <?= $m['sks'] ?> SKS)</span>
                                        </td>
                                        <?php foreach ($cpl as $c): 
                                            $isChecked = isset($peta[$m['id']][$c['id']]);
                                        ?>
                                            <td>
                                                <input class="form-check-input" type="checkbox" name="peta[<?= $m['id'] ?>][<?= $c['id'] ?>]" value="1" <?= $isChecked ? 'checked' : '' ?>>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
        </div>

        <!-- TAB 4: KETERCAPAIAN -->
        <div class="tab-pane fade" id="tab-ket" role="tabpanel">
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="card-body">
                    <h5 class="text-dark mb-3">Input Ketercapaian CPL</h5>
                    <p class="text-muted mb-4">Masukkan data persentase ketercapaian CPL per Mata Kuliah berdasarkan tahun akademik berjalan.</p>
                    <a href="<?= site_url("kurikulum/{$kurikulum['id']}/ketercapaian") ?>" class="btn btn-primary shadow-sm"><i class="bi bi-bar-chart me-1"></i> Buka Form Ketercapaian</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Script agar saat redirect kembali dari fungsi simpan, otomatis membuka Tab yang sesuai
document.addEventListener("DOMContentLoaded", function() {
    let hash = window.location.hash;
    if (hash) {
        let tabTarget = document.querySelector('button[data-bs-target="' + hash + '"]');
        if (tabTarget) {
            new bootstrap.Tab(tabTarget).show();
        }
    }
});
</script>
<?= $this->endSection() ?>