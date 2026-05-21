<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - <?= esc(env('app.namaAplikasi', 'KAPRODI')) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg,#0d3b8c 0%,#c41e3a 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; font-family: system-ui, sans-serif; }
        .login-card { background:#fff; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,.25); max-width:420px; width:100%; padding:2rem; }
        .login-card .logo { text-align:center; margin-bottom:1rem; }
        .login-card .logo img { height:64px; }
        .login-card h4 { text-align:center; color:#0d3b8c; }
        .demo-accounts { font-size:.75rem; color:#666; margin-top:1rem; background:#f8f9fa; padding:.75rem; border-radius:6px; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo">
        <img src="<?= base_url('assets/logo-utd.png') ?>" alt="UTD" onerror="this.style.display='none'">
    </div>
    <h4><?= esc(env('app.namaAplikasi', 'KAPRODI')) ?></h4>
    <p class="text-center text-muted small"><?= esc(env('app.namaPT', '')) ?></p>

    <?php if (session('error')): ?>
        <div class="alert alert-danger py-2 small"><?= esc(session('error')) ?></div>
    <?php endif ?>
    <?php if (session('errors')): foreach ((array)session('errors') as $e): ?>
        <div class="alert alert-warning py-2 small"><?= esc($e) ?></div>
    <?php endforeach; endif ?>

    <form method="post" action="<?= site_url('login') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label small">Email</label>
            <input type="email" name="email" class="form-control" autofocus required value="<?= esc(old('email')) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label small">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
    </form>

    <div class="demo-accounts">
        <strong>Akun demo (password: <code>rahasia123</code>):</strong><br>
        superadmin@utd.ac.id &middot; kaprodi@utd.ac.id &middot; dosen@utd.ac.id<br>
        gkm@utd.ac.id &middot; asesor@utd.ac.id &middot; mahasiswa@utd.ac.id
    </div>
</div>
</body>
</html>
