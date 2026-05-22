<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f4f6f9} .card-head{background:#0d3b8c;color:#fff}</style>
</head>
<body>
<div class="container py-4" style="max-width:680px">
    <div class="card shadow-sm">
        <div class="card-head p-3">
            <h5 class="mb-0">Kuesioner Tracer Study Alumni</h5>
            <small class="opacity-75"><?= esc($mhs['prodi_nama'] ?? 'Program Studi') ?></small>
        </div>
        <div class="card-body">
            <p class="text-muted small">
                Halo <strong><?= esc($mhs['nama']) ?></strong> (NIM <?= esc($mhs['nim']) ?>),
                mohon luangkan waktu sebentar untuk mengisi data karier Anda setelah lulus.
            </p>

            <form method="post" action="<?= site_url('tracer/isi/' . $token) ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Berapa bulan masa tunggu Anda sampai memperoleh pekerjaan pertama?</label>
                    <input type="number" min="0" name="masa_tunggu_bulan" class="form-control"
                           value="<?= esc($tracer['masa_tunggu_bulan'] ?? '') ?>" placeholder="contoh: 3">
                    <small class="text-muted">Isi 0 jika sudah bekerja sebelum/saat lulus. Kosongkan bila lanjut studi/belum bekerja.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kesesuaian pekerjaan dengan bidang studi</label>
                    <select name="kesesuaian_bidang_kerja" class="form-select">
                        <option value="">— pilih —</option>
                        <?php foreach (['sangat_sesuai'=>'Sangat sesuai','sesuai'=>'Sesuai','kurang_sesuai'=>'Kurang sesuai','tidak_sesuai'=>'Tidak sesuai'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= ($tracer['kesesuaian_bidang_kerja'] ?? '')===$v?'selected':'' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tingkat / lingkup tempat kerja</label>
                    <select name="tingkat_pekerjaan" class="form-select">
                        <option value="">— pilih —</option>
                        <?php foreach (['lokal'=>'Lokal/Wilayah','nasional'=>'Nasional','multinasional'=>'Multinasional','internasional'=>'Internasional','wirausaha'=>'Wirausaha'] as $v=>$l): ?>
                            <option value="<?= $v ?>" <?= ($tracer['tingkat_pekerjaan'] ?? '')===$v?'selected':'' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama perusahaan / instansi</label>
                        <input type="text" name="nama_perusahaan" class="form-control" value="<?= esc($tracer['nama_perusahaan'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jabatan / posisi</label>
                        <input type="text" name="jabatan" class="form-control" value="<?= esc($tracer['jabatan'] ?? '') ?>">
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Gaji pertama per bulan (Rp)</label>
                    <input type="text" inputmode="numeric" name="gaji_pertama" class="form-control" value="<?= esc($tracer['gaji_pertama'] ?? '') ?>" placeholder="contoh: 5000000">
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="lanjut_studi" value="1" id="ls" <?= !empty($tracer['lanjut_studi'])?'checked':'' ?>>
                    <label class="form-check-label" for="ls">Saya melanjutkan studi (S1/S2/dst)</label>
                </div>

                <button type="submit" class="btn btn-primary w-100"><i></i> Kirim Jawaban</button>
            </form>
        </div>
        <div class="card-footer text-center text-muted small">Data Anda hanya digunakan untuk keperluan akreditasi program studi.</div>
    </div>
</div>
</body>
</html>
