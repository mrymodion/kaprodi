<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survei Kepuasan Pengguna Lulusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f4f6f9} .card-head{background:#0d3b8c;color:#fff}</style>
</head>
<body>
<div class="container py-4" style="max-width:720px">
    <div class="card shadow-sm">
        <div class="card-head p-3">
            <h5 class="mb-0">Survei Kepuasan Pengguna Lulusan</h5>
            <small class="opacity-75"><?= esc($mhs['prodi_nama'] ?? 'Program Studi') ?></small>
        </div>
        <div class="card-body">
            <p class="text-muted small">
                Yth. Bapak/Ibu pimpinan/atasan, mohon penilaian terhadap kinerja alumni kami,
                <strong><?= esc($mhs['nama']) ?></strong> (NIM <?= esc($mhs['nim']) ?>),
                yang bekerja di instansi Anda. Penilaian memakai skala
                <strong>1 = Kurang, 2 = Cukup, 3 = Baik, 4 = Sangat Baik</strong>.
            </p>

            <form method="post" action="<?= site_url('kepuasan/isi/' . $token) ?>">
                <?= csrf_field() ?>
                <div class="row g-3 mb-2">
                    <div class="col-md-4"><label class="form-label">Nama penilai/atasan</label>
                        <input type="text" name="nama_atasan" class="form-control" value="<?= esc($data['nama_atasan'] ?? '') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Jabatan</label>
                        <input type="text" name="jabatan_atasan" class="form-control" value="<?= esc($data['jabatan_atasan'] ?? '') ?>"></div>
                    <div class="col-md-4"><label class="form-label">Perusahaan/Instansi</label>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= esc($data['nama_perusahaan'] ?? '') ?>"></div>
                </div>

                <table class="table table-sm align-middle">
                    <thead class="table-light"><tr><th>Aspek</th><th class="text-center" style="width:55%">Penilaian</th></tr></thead>
                    <tbody>
                    <?php foreach ($aspek as $key => $label): ?>
                        <tr>
                            <td><?= esc($label) ?></td>
                            <td>
                                <div class="btn-group w-100" role="group">
                                    <?php foreach ([1=>'Kurang',2=>'Cukup',3=>'Baik',4=>'Sangat Baik'] as $n=>$t): ?>
                                        <input type="radio" class="btn-check" name="<?= $key ?>" id="<?= $key.$n ?>" value="<?= $n ?>"
                                            <?= (int)($data[$key] ?? 0) === $n ? 'checked' : '' ?>>
                                        <label class="btn btn-outline-primary btn-sm" for="<?= $key.$n ?>"><?= $n ?><span class="d-none d-md-inline"> · <?= $t ?></span></label>
                                    <?php endforeach ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>

                <div class="mb-3">
                    <label class="form-label">Saran untuk program studi (opsional)</label>
                    <textarea name="saran" class="form-control" rows="3"><?= esc($data['saran'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Kirim Penilaian</button>
            </form>
        </div>
        <div class="card-footer text-center text-muted small">Data hanya digunakan untuk keperluan akreditasi program studi.</div>
    </div>
</div>
</body>
</html>
