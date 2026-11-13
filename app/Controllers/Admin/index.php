<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Manajemen Akun Asesor<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-person-check text-primary me-2"></i> Manajemen Akun Asesor Eksternal
        </h1>
        <a href="<?= site_url('admin/asesor/create') ?>" class="btn btn-primary shadow-sm">
            <i class="bi bi-person-plus me-1"></i> Buat Akun Asesor
        </a>
    </div>

    <?php if (session('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= esc(session('success')) ?></div>
    <?php endif ?>
    <?php if (session('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= esc(session('error')) ?></div>
    <?php endif ?>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-dark">Daftar Asesor Terdaftar</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4 text-start">Username</th>
                            <th>Email Login</th>
                            <th>Status Akun</th>
                            <th>Dibuat Pada</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($asesorList)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada akun asesor eksternal yang dibuat.</td></tr>
                        <?php endif; ?>
                        <?php foreach($asesorList as $a): ?>
                            <tr>
                                <td class="ps-4 text-start fw-bold text-dark"><?= esc($a['username']) ?></td>
                                <td><a href="mailto:<?= esc($a['email']) ?>" class="text-decoration-none"><?= esc($a['email']) ?></a></td>
                                <td><?= $a['active'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Non-Aktif</span>' ?></td>
                                <td class="text-muted small"><?= date('d M Y, H:i', strtotime($a['created_at'])) ?></td>
                                <td class="pe-4 text-end">
                                    <a href="<?= site_url('admin/asesor/' . $a['id'] . '/edit') ?>" class="btn btn-sm btn-outline-primary shadow-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="<?= site_url('admin/asesor/' . $a['id'] . '/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun asesor ini secara permanen?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>