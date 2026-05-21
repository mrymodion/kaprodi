<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-badge"></i> Detail Dosen</h4>
    <div class="d-flex gap-2">
        <?php if (auth()->user()->can('dosen.manage')): ?>
            <a href="<?= site_url("dosen/{$dosen['id']}/edit") ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        <?php endif ?>
        <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white fw-semibold">Identitas</div>
            <div class="card-body">
                <?php
                $nama = trim(($dosen['gelar_depan'] ? $dosen['gelar_depan'].' ' : '').$dosen['nama'].($dosen['gelar_belakang'] ? ', '.$dosen['gelar_belakang'] : ''));
                ?>
                <h5 class="card-title"><?= esc($nama) ?></h5>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="40%">NIDN</th><td><code><?= esc($dosen['nidn'] ?? '-') ?></code></td></tr>
                    <tr><th class="text-muted">NIP</th><td><?= esc($dosen['nip'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Jenis Kelamin</th><td><?= $dosen['jenis_kelamin'] === 'L' ? 'Laki-laki' : ($dosen['jenis_kelamin'] === 'P' ? 'Perempuan' : '-') ?></td></tr>
                    <tr><th class="text-muted">Tempat/Tgl Lahir</th><td><?= esc($dosen['tempat_lahir'] ?? '-') ?><?= $dosen['tanggal_lahir'] ? ', '.date('d M Y', strtotime($dosen['tanggal_lahir'])) : '' ?></td></tr>
                    <tr><th class="text-muted">Email</th><td><?= $dosen['email'] ? '<a href="mailto:'.esc($dosen['email']).'">'.esc($dosen['email']).'</a>' : '-' ?></td></tr>
                    <tr><th class="text-muted">No HP</th><td><?= esc($dosen['no_hp'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Bidang Keahlian</th><td><?= esc($dosen['bidang_keahlian'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Status</th><td><?= $dosen['aktif'] ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-secondary">Non-aktif</span>' ?></td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Akademik & Kepegawaian</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="45%">Pend. Terakhir</th>
                        <td><?php
                            $pdColors = ['S3'=>'success','S2'=>'primary','S1'=>'secondary'];
                            $pd = $dosen['pendidikan_terakhir'] ?? '-';
                            echo '<span class="badge bg-'.($pdColors[$pd] ?? 'light text-dark').'">'.esc($pd).'</span>';
                        ?></td></tr>
                    <tr><th class="text-muted">Jabatan Akademik</th><td><?= esc($dosen['jabatan_akademik'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">No SK JAD</th><td><?= esc($dosen['no_sk_jad'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Tgl SK JAD</th><td><?= $dosen['tgl_sk_jad'] ? date('d M Y', strtotime($dosen['tgl_sk_jad'])) : '-' ?></td></tr>
                    <tr><th class="text-muted">Linieritas</th><td><?= $dosen['linieritas'] === 'linier' ? '<span class="badge bg-success">Linier</span>' : '<span class="badge bg-warning text-dark">Tidak Linier</span>' ?></td></tr>
                    <tr><th class="text-muted">Status Kepegawaian</th><td><?= esc($dosen['status_kepegawaian'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Sertifikat Pendidik</th><td><?= $dosen['sertifikat_pendidik'] ? '<i class="bi bi-patch-check-fill text-info"></i> Ya, No: '.esc($dosen['no_serdos'] ?? '-') : '<span class="text-muted">Belum</span>' ?></td></tr>
                </table>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header bg-info text-white fw-semibold">ID Penelitian</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3" width="45%">SINTA ID</th><td><?= esc($dosen['sinta_id'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Scopus ID</th><td><?= esc($dosen['scopus_id'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Google Scholar</th><td><?= esc($dosen['google_scholar_id'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">ORCID</th><td><?= esc($dosen['orcid'] ?? '-') ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- EWMP History -->
<div class="card mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-semibold">Riwayat EWMP</span>
        <a href="<?= site_url("dosen/ewmp/{$dosen['id']}") ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-plus"></i> Input EWMP
        </a>
    </div>
    <?php if (empty($ewmpList)): ?>
        <div class="card-body text-center text-muted py-4">Belum ada data EWMP.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>TA</th>
                    <th class="text-center">Pend PS</th>
                    <th class="text-center">Pend PS Lain PT</th>
                    <th class="text-center">Pend PT Lain</th>
                    <th class="text-center">Penelitian</th>
                    <th class="text-center">PkM</th>
                    <th class="text-center">Tugas Tambahan</th>
                    <th class="text-center fw-bold">Total SKS</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($ewmpList as $e): ?>
                <tr>
                    <td><?= esc($e['ta_kode']) ?></td>
                    <td class="text-center"><?= $e['sks_pendidikan_ps'] ?></td>
                    <td class="text-center"><?= $e['sks_pendidikan_ps_lain_pt'] ?></td>
                    <td class="text-center"><?= $e['sks_pendidikan_pt_lain'] ?></td>
                    <td class="text-center"><?= $e['sks_penelitian'] ?></td>
                    <td class="text-center"><?= $e['sks_pkm'] ?></td>
                    <td class="text-center"><?= $e['sks_tugas_tambahan'] ?></td>
                    <td class="text-center">
                        <span class="badge <?= $e['total_sks'] >= 12 && $e['total_sks'] <= 16 ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= $e['total_sks'] ?>
                        </span>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer text-muted small">Total SKS normal: 12–16 SKS per semester.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
