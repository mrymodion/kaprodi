<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= $asesor ? 'Edit' : 'Buat' ?> Akun Asesor<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-person-badge text-primary me-2"></i> <?= $asesor ? 'Edit' : 'Buat' ?> Akun Asesor
        </h1>
        <a href="<?= site_url('admin/asesor') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= esc(session('error')) ?></div>
    <?php endif ?>

    <div class="card shadow-sm border-0" style="max-width: 600px;">
        <div class="card-body p-4">
            <form action="<?= $action ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="misal: asesor.lam" value="<?= esc(old('username', $asesor->username ?? '')) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Alamat Email</label>
                    <input type="email" name="email" class="form-control" placeholder="misal: asesor@laminfokom.or.id" value="<?= esc(old('email', $asesor->email ?? '')) ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Password Login</label>
                    <input type="password" name="password" class="form-control" placeholder="<?= $asesor ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password kuat' ?>" <?= $asesor ? '' : 'required' ?>>
                </div>

                <div class="mb-4 bg-light p-3 rounded">
                    <label class="form-label fw-bold mb-2">Status Akses Akun</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="activeSwitch" <?= old('active', $asesor->active ?? 1) == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="activeSwitch">Izinkan Asesor untuk Login ke Sistem</label>
                    </div>
                    <small class="text-muted d-block mt-1">Jika dimatikan, asesor tidak akan bisa masuk dan melihat data prodi mana pun.</small>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                        <i class="bi bi-floppy me-1"></i> Simpan Data Asesor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>