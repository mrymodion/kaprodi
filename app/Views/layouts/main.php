<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'KAPRODI') ?> - <?= esc(env('app.namaAplikasi', 'KAPRODI')) ?></title>
    <link rel="icon" href="<?= base_url('assets/logo-utd.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .navbar-brand img { height:32px; margin-right:8px; }
        .stat-card { border-left:4px solid var(--bs-primary); }
        .sidebar { min-height: calc(100vh - 56px); background:#fff; border-right:1px solid #dee2e6; }
        .sidebar .nav-link { color:#333; border-radius:0; }
        .sidebar .nav-link.active { background:#0d6efd; color:#fff; }
        .badge-akreditasi { font-size: .75rem; }
    </style>
</head>
<body>
<nav class="navbar navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/logo-utd.png') ?>" alt="UTD">
            <strong><?= esc(env('app.namaAplikasi', 'KAPRODI')) ?></strong>
            <small class="ms-2 opacity-75"><?= esc(env('app.namaPT', '')) ?></small>
        </a>
        <span class="text-white"><i class="bi bi-person-circle"></i> Admin</span>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-2 sidebar p-0">
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?= base_url('/') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a class="nav-link" href="#"><i class="bi bi-building"></i> Prodi</a>
                <a class="nav-link" href="#"><i class="bi bi-people"></i> DTPR (Dosen)</a>
                <a class="nav-link" href="#"><i class="bi bi-person-badge"></i> Mahasiswa</a>
                <a class="nav-link" href="#"><i class="bi bi-book"></i> Kurikulum & CPL</a>
                <a class="nav-link" href="#"><i class="bi bi-journal-text"></i> Penelitian</a>
                <a class="nav-link" href="#"><i class="bi bi-megaphone"></i> PkM</a>
                <a class="nav-link" href="#"><i class="bi bi-link-45deg"></i> Kerjasama</a>
                <a class="nav-link" href="#"><i class="bi bi-clipboard-check"></i> Penilaian LAM</a>
                <a class="nav-link" href="#"><i class="bi bi-file-earmark-spreadsheet"></i> LKPS</a>
                <a class="nav-link" href="#"><i class="bi bi-file-earmark-word"></i> LED</a>
                <a class="nav-link" href="#"><i class="bi bi-gear"></i> Pengaturan</a>
            </nav>
        </aside>
        <main class="col-md-10 p-4">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<footer class="text-center text-muted small py-3">
    &copy; <?= date('Y') ?> <?= esc(env('app.namaPT', '')) ?> &middot; KAPRODI v0.1
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
