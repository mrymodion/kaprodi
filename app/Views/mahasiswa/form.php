<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?><?= !empty($mhs) ? 'Edit' : 'Tambah' ?> Mahasiswa<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-person-lines-fill text-primary me-2"></i> <?= !empty($mhs) ? 'Edit' : 'Tambah' ?> Mahasiswa
        </h1>
        <a href="<?= site_url('mahasiswa') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0" style="max-width: 800px;">
        <div class="card-body p-4">
            <form action="<?= $action ?>" method="POST">
                <?= csrf_field() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">NIM <span class="text-danger">*</span></label>
                        <input type="text" name="nim" class="form-control" value="<?= esc(old('nim', $mhs['nim'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nama Mahasiswa <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" value="<?= esc(old('nama', $mhs['nama'] ?? '')) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select">
                            <option value="L" <?= old('jenis_kelamin', $mhs['jenis_kelamin'] ?? '') == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= old('jenis_kelamin', $mhs['jenis_kelamin'] ?? '') == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Provinsi Asal</label>
                        <input type="text" name="provinsi_asal" class="form-control" value="<?= esc(old('provinsi_asal', $mhs['provinsi_asal'] ?? '')) ?>" placeholder="misal: Jawa Barat">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tahun Masuk</label>
                        <select name="tahun_masuk_id" class="form-select">
                            <option value="">-- Pilih TA Masuk --</option>
                            <?php foreach($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= old('tahun_masuk_id', $mhs['tahun_masuk_id'] ?? '') == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Status Mahasiswa</label>
                        <select name="status" class="form-select">
                            <?php foreach(['aktif'=>'Aktif', 'lulus'=>'Lulus', 'cuti'=>'Cuti', 'do'=>'Drop Out', 'keluar'=>'Keluar/Undur Diri'] as $k => $v): ?>
                                <option value="<?= $k ?>" <?= old('status', $mhs['status'] ?? 'aktif') == $k ? 'selected' : '' ?>><?= $v ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-12 mt-4 mb-2"><h6 class="border-bottom pb-2 fw-bold text-success">Khusus Status Lulusan (Opsional)</h6></div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold">IPK Lulus</label>
                        <input type="number" step="0.01" max="4.00" name="ipk" class="form-control" value="<?= esc(old('ipk', $mhs['ipk'] ?? '')) ?>" placeholder="3.50">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Masa Studi (Bulan)</label>
                        <input type="number" name="masa_studi_bulan" class="form-control" value="<?= esc(old('masa_studi_bulan', $mhs['masa_studi_bulan'] ?? '')) ?>" placeholder="misal: 48">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Tanggal Lulus</label>
                        <input type="date" name="tgl_lulus" class="form-control" value="<?= esc(old('tgl_lulus', $mhs['tgl_lulus'] ?? '')) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">No. HP / WA</label>
                        <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp', $mhs['no_hp'] ?? '')) ?>" placeholder="Wajib agar bisa dikirimi WA Tracer Study">
                    </div>
                </div>
                
                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                        <i class="bi bi-floppy me-1"></i> Simpan Data Mahasiswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>