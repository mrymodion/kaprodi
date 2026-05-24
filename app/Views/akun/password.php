<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-key"></i> Ganti Password</h4>
    <a href="<?= site_url('akun') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('error')): ?>
<div class="alert alert-danger"><?= esc(session('error')) ?></div>
<?php endif ?>

<div class="card"><div class="card-body">
<form method="post" action="<?= site_url('akun/password') ?>" style="max-width:420px">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label fw-semibold">Password Baru</label>
        <input type="password" name="password" class="form-control" minlength="8" required>
        <small class="text-muted">Minimal 8 karakter.</small>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold">Ulangi Password Baru</label>
        <input type="password" name="password_confirm" class="form-control" minlength="8" required>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Ganti Password</button>
</form>
</div></div>

<?= $this->endSection() ?>
