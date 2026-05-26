<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Validasi Surat Tugas Mengajar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5" style="max-width:680px">
    <div class="text-center mb-4">
        <h4 class="mb-0"><i class="bi bi-shield-check"></i> Validasi Surat Tugas Mengajar</h4>
    </div>

    <?php if (empty($valid)): ?>
        <div class="card border-danger shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-x-octagon-fill text-danger" style="font-size:3rem"></i>
                <h5 class="mt-3 text-danger">Dokumen Tidak Valid</h5>
                <p class="text-muted mb-0">Kode validasi tidak dikenali atau data tidak ditemukan. Pastikan QR dipindai dari dokumen Surat Tugas Mengajar yang resmi.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card border-success shadow-sm">
            <div class="card-header bg-success text-white d-flex align-items-center gap-2">
                <i class="bi bi-patch-check-fill"></i> <span class="fw-semibold">Dokumen Terverifikasi</span>
            </div>
            <div class="card-body">
                <p class="small text-muted">Data berikut sesuai dengan basis data resmi program studi pada saat pemindaian.</p>
                <table class="table table-sm">
                    <tr><th style="width:35%">Nama Dosen</th><td><?= esc($namaDosen) ?></td></tr>
                    <?php if (!empty($nidn)): ?><tr><th>NIDN</th><td><?= esc($nidn) ?></td></tr><?php endif ?>
                    <tr><th>Program Studi</th><td><?= esc($prodiNama) ?></td></tr>
                    <?php if (!empty($ptNama)): ?><tr><th>Perguruan Tinggi</th><td><?= esc($ptNama) ?></td></tr><?php endif ?>
                    <tr><th>Tahun Akademik</th><td><?= esc($ta['kode'] ?? '-') ?></td></tr>
                    <?php if (!empty($signer)): ?><tr><th>Ditandatangani</th><td><?= esc($signer) ?> <span class="text-muted">(Kaprodi)</span></td></tr><?php endif ?>
                </table>

                <h6 class="mt-3">Mata Kuliah Diampu</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered align-middle">
                        <thead class="table-light"><tr><th>Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Kelas</th></tr></thead>
                        <tbody>
                        <?php foreach ($list as $r): ?>
                            <tr>
                                <td><small><?= esc($r['kode_mk'] ?? '-') ?></small></td>
                                <td><?= esc($r['nama_mk']) ?></td>
                                <td class="text-center"><?= (float) $r['sks'] ?></td>
                                <td class="text-center"><?= (int) $r['jumlah_kelas'] ?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>

    <p class="text-center text-muted small mt-3 mb-0"><?= esc(date('d-m-Y H:i')) ?> &middot; Sistem Validasi Dokumen</p>
</div>
</body>
</html>
