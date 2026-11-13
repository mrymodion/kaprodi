<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Study - <?= esc($mhs['prodi_nama']) ?></title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .tracer-header { background: linear-gradient(135deg, #0d3b8c, #1a56c8); color: white; padding: 40px 0; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; }
        .card-form { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-top: -30px; margin-bottom: 50px; }
        .form-label { font-weight: 600; color: #495057; }
        .btn-submit { background-color: #0d3b8c; border-color: #0d3b8c; padding: 10px 20px; font-weight: 600; border-radius: 8px; }
        .btn-submit:hover { background-color: #0a2d6c; }
        .section-title { font-size: 1.1rem; font-weight: 700; color: #0d3b8c; border-bottom: 2px solid #e9ecef; padding-bottom: 10px; margin-bottom: 20px; margin-top: 30px; }
    </style>
</head>
<body>

    <div class="tracer-header text-center">
        <div class="container">
            <h2 class="fw-bold mb-2">Tracer Study Alumni</h2>
            <p class="lead mb-0"><?= esc($mhs['prodi_nama']) ?></p>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-form">
                    <div class="card-body p-4 p-md-5">
                        
                        <div class="alert alert-light border-info mb-4" role="alert">
                            <h5 class="alert-heading text-info fw-bold"><i class="bi bi-person-badge me-2"></i> Halo, <?= esc($mhs['nama']) ?>!</h5>
                            <p class="mb-0 text-muted">NIM: <strong><?= esc($mhs['nim']) ?></strong> &middot; Lulusan: <strong><?= date('Y', strtotime($mhs['tgl_lulus'])) ?></strong></p>
                            <hr>
                            <p class="mb-0 small text-muted">Partisipasi Anda sangat berharga untuk mengevaluasi dan meningkatkan relevansi kurikulum, serta berdampak langsung pada nilai akreditasi Program Studi kita.</p>
                        </div>

                        <form action="<?= site_url('tracer/isi/' . $token) ?>" method="POST">
                            <?= csrf_field() ?>

                            <div class="section-title">A. Transisi ke Dunia Kerja</div>
                            
                            <div class="mb-4">
                                <label for="masa_tunggu_bulan" class="form-label">Berapa bulan waktu yang Anda butuhkan untuk mendapatkan pekerjaan pertama setelah lulus? <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="masa_tunggu_bulan" name="masa_tunggu_bulan" 
                                           value="<?= old('masa_tunggu_bulan', $data['masa_tunggu_bulan'] ?? '') ?>" 
                                           min="0" required placeholder="Contoh: 3">
                                    <span class="input-group-text bg-light">Bulan</span>
                                </div>
                                <div class="form-text">Isi dengan '0' jika Anda sudah bekerja sebelum lulus.</div>
                            </div>

                            <div class="mb-4">
                                <label for="kesesuaian_bidang_kerja" class="form-label">Bagaimana kesesuaian antara bidang studi dengan pekerjaan Anda saat ini? <span class="text-danger">*</span></label>
                                <select class="form-select" id="kesesuaian_bidang_kerja" name="kesesuaian_bidang_kerja" required>
                                    <option value="">-- Pilih Tingkat Kesesuaian --</option>
                                    <?php 
                                        $opsiKesesuaian = ['sangat_sesuai' => 'Sangat Sesuai', 'sesuai' => 'Sesuai', 'kurang_sesuai' => 'Kurang Sesuai', 'tidak_sesuai' => 'Tidak Sesuai'];
                                        $valKesesuaian = old('kesesuaian_bidang_kerja', $data['kesesuaian_bidang_kerja'] ?? '');
                                        foreach ($opsiKesesuaian as $k => $v) {
                                            $sel = ($valKesesuaian === $k) ? 'selected' : '';
                                            echo "<option value=\"$k\" $sel>$v</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="section-title">B. Profil Tempat Kerja Saat Ini</div>

                            <div class="mb-3">
                                <label for="tingkat_pekerjaan" class="form-label">Tingkat tempat kerja Anda saat ini <span class="text-danger">*</span></label>
                                <select class="form-select" id="tingkat_pekerjaan" name="tingkat_pekerjaan" required>
                                    <option value="">-- Pilih Tingkat/Skala --</option>
                                    <?php 
                                        $opsiTingkat = ['lokal' => 'Lokal / Wilayah', 'nasional' => 'Nasional', 'internasional' => 'Multinasional / Internasional'];
                                        $valTingkat = old('tingkat_pekerjaan', $data['tingkat_pekerjaan'] ?? '');
                                        foreach ($opsiTingkat as $k => $v) {
                                            $sel = ($valTingkat === $k) ? 'selected' : '';
                                            echo "<option value=\"$k\" $sel>$v</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan / Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" 
                                       value="<?= old('nama_perusahaan', $data['nama_perusahaan'] ?? '') ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="jabatan" class="form-label">Jabatan / Posisi Anda <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="jabatan" name="jabatan" 
                                       value="<?= old('jabatan', $data['jabatan'] ?? '') ?>" required>
                            </div>

                            <div class="section-title">C. Kontak Atasan Langsung (Pengguna Lulusan)</div>
                            <p class="small text-muted mb-3">Data ini diperlukan oleh program studi untuk mengirimkan Survei Kepuasan Pengguna Lulusan (bersifat opsional namun sangat direkomendasikan).</p>

                            <div class="mb-3">
                                <label for="nama_atasan" class="form-label">Nama Atasan Langsung</label>
                                <input type="text" class="form-control" id="nama_atasan" name="nama_atasan" 
                                       value="<?= old('nama_atasan', $data['nama_atasan'] ?? '') ?>" placeholder="Bapak/Ibu ...">
                            </div>

                            <div class="mb-4">
                                <label for="no_hp_atasan" class="form-label">No. HP / WhatsApp Atasan</label>
                                <input type="text" class="form-control" id="no_hp_atasan" name="no_hp_atasan" 
                                       value="<?= old('no_hp_atasan', $data['no_hp_atasan'] ?? '') ?>" placeholder="0812xxxxxx">
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-primary btn-submit btn-lg">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Data Tracer Study
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
                
                <div class="text-center text-muted small mb-5">
                    &copy; <?= date('Y') ?> Tracer Study Alumni &middot; <?= esc($mhs['prodi_nama']) ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>