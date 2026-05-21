<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Detail Mahasiswa</h4>
    <div class="d-flex gap-2">
        <?php if (auth()->user()->can('mahasiswa.manage')): ?>
            <a href="<?= site_url("mahasiswa/{$mhs['id']}/edit") ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
        <?php endif ?>
        <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white fw-semibold">Identitas</div>
            <div class="card-body">
                <h5 class="card-title"><?= esc($mhs['nama']) ?></h5>
                <?php
                $statusColor = match($mhs['status']) {
                    'aktif'=>'success','lulus'=>'primary','cuti'=>'warning','do'=>'danger',default=>'secondary'
                };
                ?>
                <span class="badge bg-<?= $statusColor ?> mb-3"><?= ucfirst($mhs['status']) ?></span>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="40%">NIM</th><td><code><?= esc($mhs['nim']) ?></code></td></tr>
                    <tr><th class="text-muted">Jenis Kelamin</th><td><?= $mhs['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($mhs['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') ?></td></tr>
                    <tr><th class="text-muted">Tempat/Tgl Lahir</th><td><?= esc($mhs['tempat_lahir'] ?? '-') ?><?= $mhs['tanggal_lahir'] ? ', '.date('d M Y', strtotime($mhs['tanggal_lahir'])) : '' ?></td></tr>
                    <tr><th class="text-muted">Email</th><td><?= $mhs['email'] ? '<a href="mailto:'.esc($mhs['email']).'">'.esc($mhs['email']).'</a>' : '-' ?></td></tr>
                    <tr><th class="text-muted">No HP</th><td><?= esc($mhs['no_hp'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Kota Asal</th><td><?= esc($mhs['kota_asal'] ?? '-') ?><?= $mhs['provinsi_asal'] ? ', '.esc($mhs['provinsi_asal']) : '' ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Data Akademik</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="45%">Jalur Masuk</th><td><?= esc($mhs['jalur_masuk'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">IPK</th><td><?= $mhs['ipk'] ? '<span class="fw-bold fs-5">'.number_format($mhs['ipk'],2).'</span>' : '-' ?></td></tr>
                    <tr><th class="text-muted">Masa Studi</th><td><?= $mhs['masa_studi_bulan'] ? $mhs['masa_studi_bulan'].' bulan' : '-' ?></td></tr>
                    <tr><th class="text-muted">Tanggal Lulus</th><td><?= $mhs['tgl_lulus'] ? date('d M Y', strtotime($mhs['tgl_lulus'])) : '-' ?></td></tr>
                </table>
            </div>
        </div>

        <?php if ($mhs['status'] === 'lulus'): ?>
        <div class="card mt-3">
            <div class="card-header bg-info text-white fw-semibold">Tracer Study</div>
            <?php if ($tracer): ?>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="45%">Masa Tunggu</th><td><?= $tracer['masa_tunggu_bulan'] !== null ? $tracer['masa_tunggu_bulan'].' bulan' : '-' ?></td></tr>
                    <tr><th class="text-muted">Kesesuaian Bidang</th><td><?= esc(str_replace('_',' ', ucfirst($tracer['kesesuaian_bidang_kerja'] ?? '-'))) ?></td></tr>
                    <tr><th class="text-muted">Tingkat Pekerjaan</th><td><?= esc(ucfirst($tracer['tingkat_pekerjaan'] ?? '-')) ?></td></tr>
                    <tr><th class="text-muted">Perusahaan</th><td><?= esc($tracer['nama_perusahaan'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Jabatan</th><td><?= esc($tracer['jabatan'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Lanjut Studi</th><td><?= $tracer['lanjut_studi'] ? '<span class="badge bg-success">Ya</span>' : 'Tidak' ?></td></tr>
                </table>
                <a href="<?= site_url("mahasiswa/tracer/{$mhs['id']}") ?>" class="btn btn-sm btn-outline-primary mt-2"><i class="bi bi-pencil"></i> Edit Tracer</a>
            </div>
            <?php else: ?>
            <div class="card-body">
                <p class="text-muted small mb-2">Belum ada data tracer study.</p>
                <a href="<?= site_url("mahasiswa/tracer/{$mhs['id']}") ?>" class="btn btn-sm btn-warning"><i class="bi bi-plus-circle"></i> Isi Tracer Study</a>
            </div>
            <?php endif ?>
        </div>
        <?php endif ?>
    </div>
</div>

<?= $this->endSection() ?>
