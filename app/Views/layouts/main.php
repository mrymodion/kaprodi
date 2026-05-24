<?php
$user = function_exists('auth') && auth()->loggedIn() ? auth()->user() : null;
$role = $user ? implode(', ', $user->getGroups() ?: []) : 'guest';

// Precompute semua permission yang dipakai layout — 1× per request,
// bukan 16× per call $user->can() yang masing-masing iterasi groups/permissions Shield.
$perms = [];
if ($user) {
    foreach (['prodi.manage','dosen.manage','mahasiswa.manage','kurikulum.manage',
              'tridharma.manage','tridharma.input_own','kerjasama.manage',
              'penilaian.input','lkps.generate','led.generate',
              'users.manage','instrumen.manage'] as $p) {
        $perms[$p] = $user->can($p);
    }
}
$can = fn($p) => $perms[$p] ?? false;

// Cache env() — masing-masing call mem-parse .env file di CI4
$appName = env('app.namaAplikasi', 'KAPRODI');
$appPt   = env('app.namaPT', '');

// Pemilih prodi aktif (khusus superadmin): daftar prodi + nama yang aktif
$isSuperadmin    = $user && $user->inGroup('superadmin');
$isDosen         = $user && $user->inGroup('dosen');
$prodiSwitcher   = [];
$activeProdiNama = null;
if ($isSuperadmin) {
    $activeProdiId = session('active_prodi_id');
    $prodiSwitcher = \Config\Database::connect()
        ->table('prodi')->select('id, nama, jenjang')
        ->orderBy('nama', 'ASC')->get()->getResultArray();
    foreach ($prodiSwitcher as $p) {
        if ($activeProdiId && (int) $p['id'] === (int) $activeProdiId) {
            $activeProdiNama = $p['nama'] . ' (' . $p['jenjang'] . ')';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'KAPRODI') ?> - <?= esc($appName) ?></title>
    <link rel="icon" href="<?= base_url('assets/logo-utd.png') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .navbar-brand img { height:32px; margin-right:8px; }
        .navbar-brand strong { font-size:1rem; }
        .stat-card { border-left:4px solid var(--bs-primary); }
        .sidebar { background:#fff; }
        .sidebar .nav-link { color:#333; border-radius:0; padding:.5rem 1rem; }
        .sidebar .nav-link:hover { background:#f8f9fa; }
        .sidebar .nav-link.active { background:#0d3b8c; color:#fff; }
        .sidebar .nav-section { font-size:.7rem; text-transform:uppercase; color:#888; padding:.75rem 1rem .25rem; }
        .badge-akreditasi { font-size: .75rem; }
        /* Sidebar sebagai kolom statis di layar md+ */
        @media (min-width: 768px) {
            .sidebar { min-height: calc(100vh - 56px); border-right:1px solid #dee2e6; }
        }
        /* Lebar panel geser di mobile */
        @media (max-width: 767.98px) {
            #sidebarMenu.offcanvas-md { width: 80vw; max-width: 320px; }
        }
        /* Cegah tabel lebar memecah layout di mobile */
        .table-responsive { -webkit-overflow-scrolling: touch; }
        /* Grup tombol ikut membungkus bila sempit */
        .btn-group { flex-wrap: wrap; }
        /* Header halaman (judul + tombol aksi) membungkus rapi di layar kecil */
        @media (max-width: 575.98px) {
            .d-flex.justify-content-between.align-items-center { flex-wrap: wrap; gap: .5rem; }
            main.col-md-10 h4 { font-size: 1.15rem; }
        }
    </style>
</head>
<body>
<nav class="navbar navbar-dark" style="background:#0d3b8c">
    <div class="container-fluid">
        <button class="navbar-toggler border-0 me-2 d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Buka menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand d-flex align-items-center text-truncate" href="<?= base_url('/') ?>">
            <img src="<?= base_url('assets/logo-utd.png') ?>" alt="UTD" onerror="this.style.display='none'">
            <strong><?= esc($appName) ?></strong>
            <small class="ms-2 opacity-75 d-none d-lg-inline"><?= esc($appPt) ?></small>
        </a>
        <div class="d-flex align-items-center gap-2 ms-auto">
        <?php if ($isSuperadmin): ?>
        <div class="dropdown">
            <button class="btn btn-sm btn-light dropdown-toggle text-truncate" style="max-width:45vw" data-bs-toggle="dropdown">
                <i class="bi bi-building"></i>
                <span class="d-none d-sm-inline"><?= $activeProdiNama ? esc($activeProdiNama) : 'Pilih Prodi' ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" style="max-height:60vh; overflow-y:auto;">
                <li><h6 class="dropdown-header">Prodi aktif</h6></li>
                <?php foreach ($prodiSwitcher as $p): ?>
                    <li>
                        <a class="dropdown-item <?= (int) session('active_prodi_id') === (int) $p['id'] ? 'active' : '' ?>"
                           href="<?= site_url('prodi/switch/' . $p['id']) ?>">
                            <?= esc($p['nama']) ?> <span class="text-muted small">(<?= esc($p['jenjang']) ?>)</span>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <?php endif ?>
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <span class="d-none d-sm-inline"><?= esc($user->username ?? 'guest') ?></span>
                <span class="badge bg-light text-dark d-none d-md-inline"><?= esc($role) ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><span class="dropdown-item-text small text-muted"><?= esc($user->email ?? '-') ?></span></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0">
        <aside class="offcanvas-md offcanvas-start sidebar" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
            <div class="offcanvas-header border-bottom d-md-none">
                <h6 class="offcanvas-title mb-0" id="sidebarMenuLabel"><i class="bi bi-list"></i> Menu</h6>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Tutup"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column p-0">
            <div class="nav-section">Utama</div>
            <nav class="nav flex-column">
                <a class="nav-link active" href="<?= site_url('/') ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </nav>
            <?php if ($isDosen): ?>
                <div class="nav-section">Akun Saya</div>
                <nav class="nav flex-column">
                    <a class="nav-link <?= uri_string() === 'akun' ? 'active' : '' ?>" href="<?= site_url('akun') ?>"><i class="bi bi-person-circle"></i> Profil Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/publikasi') ? 'active' : '' ?>" href="<?= site_url('akun/publikasi') ?>"><i class="bi bi-file-earmark-text"></i> Publikasi Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/penelitian') ? 'active' : '' ?>" href="<?= site_url('akun/penelitian') ?>"><i class="bi bi-journal-text"></i> Penelitian Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/pkm') ? 'active' : '' ?>" href="<?= site_url('akun/pkm') ?>"><i class="bi bi-megaphone"></i> PkM Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/hki') ? 'active' : '' ?>" href="<?= site_url('akun/hki') ?>"><i class="bi bi-shield-check"></i> HKI Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/rekognisi') ? 'active' : '' ?>" href="<?= site_url('akun/rekognisi') ?>"><i class="bi bi-award"></i> Rekognisi Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/pengembangan') ? 'active' : '' ?>" href="<?= site_url('akun/pengembangan') ?>"><i class="bi bi-mortarboard"></i> Pengembangan Diri</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/ewmp') ? 'active' : '' ?>" href="<?= site_url('akun/ewmp') ?>"><i class="bi bi-table"></i> EWMP Saya</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/profil') ? 'active' : '' ?>" href="<?= site_url('akun/profil') ?>"><i class="bi bi-pencil-square"></i> Edit Profil</a>
                    <a class="nav-link <?= str_starts_with(uri_string(), 'akun/password') ? 'active' : '' ?>" href="<?= site_url('akun/password') ?>"><i class="bi bi-key"></i> Ganti Password</a>
                </nav>
            <?php endif ?>
            <?php if ($can('prodi.manage') || $can('dosen.manage') || $can('mahasiswa.manage')): ?>
                <div class="nav-section">Data Akreditasi</div>
                <nav class="nav flex-column">
                    <?php if ($can('prodi.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'prodi') && !str_starts_with(uri_string(), 'prodi/switch') ? 'active' : '' ?>" href="<?= site_url('prodi') ?>"><i class="bi bi-building"></i> Prodi</a><?php endif ?>
                    <?php if ($can('prodi.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'vmts') ? 'active' : '' ?>" href="<?= site_url('vmts') ?>"><i class="bi bi-bullseye"></i> VMTS</a><?php endif ?>
                    <?php if ($can('prodi.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'sarpras') ? 'active' : '' ?>" href="<?= site_url('sarpras') ?>"><i class="bi bi-building-gear"></i> Sarana & Prasarana</a><?php endif ?>
                    <?php if ($can('prodi.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'tendik') ? 'active' : '' ?>" href="<?= site_url('tendik') ?>"><i class="bi bi-person-workspace"></i> Tenaga Kependidikan</a><?php endif ?>
                    <?php if ($can('prodi.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'keuangan') ? 'active' : '' ?>" href="<?= site_url('keuangan') ?>"><i class="bi bi-cash-coin"></i> Keuangan</a><?php endif ?>
                    <?php if ($can('dosen.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'dosen') && !str_starts_with(uri_string(), 'dosen/rekognisi') ? 'active' : '' ?>" href="<?= site_url('dosen') ?>"><i class="bi bi-people"></i> DTPR (Dosen)</a><?php endif ?>
                    <?php if ($can('dosen.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'dosen/rekognisi') ? 'active' : '' ?>" href="<?= site_url('dosen/rekognisi') ?>"><i class="bi bi-award"></i> Rekognisi DTPR</a><?php endif ?>
                    <?php if ($can('mahasiswa.manage')): ?>
                        <a class="nav-link <?= str_starts_with(uri_string(), 'mahasiswa') ? 'active' : '' ?>" href="<?= site_url('mahasiswa') ?>"><i class="bi bi-person-badge"></i> Mahasiswa</a>
                    <?php endif ?>
                    <?php if ($can('kurikulum.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'kurikulum') ? 'active' : '' ?>" href="<?= site_url('kurikulum') ?>"><i class="bi bi-book"></i> Kurikulum & CPL</a><?php endif ?>
                    <?php if ($can('tridharma.manage') || $can('tridharma.input_own')): ?>
                        <a class="nav-link <?= str_starts_with(uri_string(), 'penelitian') ? 'active' : '' ?>" href="<?= site_url('penelitian') ?>"><i class="bi bi-journal-text"></i> Penelitian</a>
                        <a class="nav-link <?= str_starts_with(uri_string(), 'pkm') ? 'active' : '' ?>" href="<?= site_url('pkm') ?>"><i class="bi bi-megaphone"></i> PkM</a>
                        <a class="nav-link <?= str_starts_with(uri_string(), 'publikasi') ? 'active' : '' ?>" href="<?= site_url('publikasi') ?>"><i class="bi bi-file-earmark-text"></i> Publikasi & HKI</a>
                    <?php endif ?>
                    <?php if ($can('kerjasama.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'kerjasama') ? 'active' : '' ?>" href="<?= site_url('kerjasama') ?>"><i class="bi bi-link-45deg"></i> Kerjasama</a><?php endif ?>
                </nav>
            <?php endif ?>
            <?php if ($can('prodi.manage')): ?>
                <div class="nav-section">Tata Kelola & Suplemen</div>
                <nav class="nav flex-column">
                    <?php foreach (\App\Controllers\BorangController::entities() as $bslug => $bdef): ?>
                        <a class="nav-link <?= str_starts_with(uri_string(), 'borang/' . $bslug) ? 'active' : '' ?>" href="<?= site_url('borang/' . $bslug) ?>">
                            <i class="bi <?= esc($bdef['icon']) ?>"></i> <?= esc($bdef['title']) ?>
                        </a>
                    <?php endforeach ?>
                </nav>
            <?php endif ?>
            <?php if ($can('penilaian.input') || $can('lkps.generate') || $can('led.generate')): ?>
                <div class="nav-section">Akreditasi</div>
                <nav class="nav flex-column">
                    <?php if ($can('penilaian.input')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'kesiapan') ? 'active' : '' ?>" href="<?= site_url('kesiapan') ?>"><i class="bi bi-clipboard-data"></i> Kesiapan Akreditasi</a><?php endif ?>
                    <?php if ($can('penilaian.input')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'penilaian') ? 'active' : '' ?>" href="<?= site_url('penilaian') ?>"><i class="bi bi-clipboard-check"></i> Penilaian LAM</a><?php endif ?>
                    <?php if ($can('lkps.generate')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'lkps') ? 'active' : '' ?>" href="<?= site_url('lkps') ?>"><i class="bi bi-file-earmark-spreadsheet"></i> LKPS</a><?php endif ?>
                    <?php if ($can('led.generate')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'led') ? 'active' : '' ?>" href="<?= site_url('led') ?>"><i class="bi bi-file-earmark-word"></i> LED</a><?php endif ?>
                </nav>
            <?php endif ?>
            <?php if ($can('users.manage') || $can('instrumen.manage')): ?>
                <div class="nav-section">Administrasi</div>
                <nav class="nav flex-column">
                    <?php if ($can('users.manage')): ?><a class="nav-link" href="<?= site_url('admin/users') ?>"><i class="bi bi-person-gear"></i> Manajemen User</a><?php endif ?>
                    <?php if ($can('instrumen.manage')): ?><a class="nav-link <?= str_starts_with(uri_string(), 'instrumen') ? 'active' : '' ?>" href="<?= site_url('instrumen') ?>"><i class="bi bi-sliders"></i> Instrumen LAM</a><?php endif ?>
                </nav>
            <?php endif ?>
            </div>
        </aside>
        </div>
        <main class="col-md-10 p-3 p-md-4">
            <?php if (session('message')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= esc(session('message')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php endif ?>
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>
<footer class="text-center text-muted small py-3">
    &copy; <?= date('Y') ?> <?= esc($appPt) ?> &middot; KAPRODI v0.4
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
