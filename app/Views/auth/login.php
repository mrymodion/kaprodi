<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= esc(env('app.namaAplikasi', 'KAPRODI')) ?></title>
    <link rel="icon" href="<?= base_url('assets/logo-utd.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg,#0d3b8c 0%,#c41e3a 100%);
            min-height: 100vh; min-height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            font-family: system-ui, sans-serif;
            padding: 1rem;
        }
        .login-card {
            background:#fff; border-radius:12px; box-shadow:0 20px 60px rgba(0,0,0,.25);
            max-width:420px; width:100%; padding:2rem;
        }
        .login-card .logo { text-align:center; margin-bottom:1rem; }
        .login-card .logo img { height:64px; }
        .login-card h4 { text-align:center; color:#0d3b8c; margin-bottom:.25rem; }
        /* Cegah auto-zoom iOS saat fokus input (font < 16px memicu zoom) */
        .login-card .form-control { font-size:16px; }
        @media (max-width: 480px) {
            .login-card { padding:1.5rem 1.25rem; border-radius:14px; }
            .login-card .logo img { height:56px; }
        }
    </style>
</head>
<body>
<div class="login-card">
    <div class="logo">
        <img src="<?= base_url('assets/logo-utd.png') ?>" alt="Logo" onerror="this.style.display='none'">
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
            <input type="email" name="email" class="form-control" autocomplete="username" inputmode="email" autofocus required value="<?= esc(old('email')) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label small">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" autocomplete="current-password" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePw" tabindex="-1" aria-label="Tampilkan password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">
            <i class="bi bi-box-arrow-in-right"></i> Masuk
        </button>
    </form>
</div>

<script>
document.getElementById('togglePw').addEventListener('click', function () {
    var pw = document.getElementById('password');
    var ic = this.querySelector('i');
    if (pw.type === 'password') { pw.type = 'text'; ic.className = 'bi bi-eye-slash'; }
    else { pw.type = 'password'; ic.className = 'bi bi-eye'; }
});
</script>
</body>
</html>
