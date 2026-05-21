<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h3 class="mb-4">Dashboard Kaprodi</h3>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card stat-card shadow-sm">
        <div class="card-body"><div class="text-muted small">Program Studi</div>
        <div class="h3 mb-0"><?= $jumlahProdi ?></div></div></div></div>
    <div class="col-md-3"><div class="card stat-card shadow-sm" style="border-color:#198754">
        <div class="card-body"><div class="text-muted small">Dosen (DTPR) Aktif</div>
        <div class="h3 mb-0"><?= $jumlahDosen ?></div></div></div></div>
    <div class="col-md-3"><div class="card stat-card shadow-sm" style="border-color:#ffc107">
        <div class="card-body"><div class="text-muted small">Mahasiswa Aktif</div>
        <div class="h3 mb-0"><?= $jumlahMhs ?></div></div></div></div>
    <div class="col-md-3"><div class="card stat-card shadow-sm" style="border-color:#6f42c1">
        <div class="card-body"><div class="text-muted small">Instrumen LAM</div>
        <div class="h3 mb-0"><?= $jumlahInstr ?></div></div></div></div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white"><strong>Daftar Program Studi</strong></div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Kode PDDikti</th><th>Nama Prodi</th><th>Jenjang</th>
                    <th>Akreditasi Saat Ini</th><th>Instrumen Akreditasi</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($prodiList as $p): ?>
                <tr>
                    <td><code><?= esc($p['kode_pddikti']) ?></code></td>
                    <td><?= esc($p['nama']) ?></td>
                    <td><span class="badge bg-secondary"><?= esc($p['jenjang']) ?></span></td>
                    <td><span class="badge bg-info badge-akreditasi"><?= esc($p['akreditasi_saat_ini']) ?></span></td>
                    <td><?= $p['instrumen'] ? '<span class="badge bg-success">'.esc($p['instrumen']).'</span>' : '<span class="text-muted">belum dipetakan</span>' ?></td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>

<div class="alert alert-info mt-4">
    <strong><i class="bi bi-info-circle"></i> Status fondasi:</strong>
    Migrasi & seeder LAM INFOKOM 2.0 D3 sudah siap (6 kriteria, 82 butir, 31 tabel LKPS).
    Modul fungsional (DTPR, Mahasiswa, Tridharma, Kurikulum, LKPS Generator, LED+Groq) akan dibangun pada Fase berikutnya.
</div>

<?= $this->endSection() ?>
