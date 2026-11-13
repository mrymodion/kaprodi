<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Kelola Mata Kuliah Diampu<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-journal-check text-primary me-2"></i> Penugasan Mengajar
            </h1>
            <p class="text-muted mb-0">Dosen: <strong><?= esc($namaDosen) ?></strong></p>
        </div>
        <a href="<?= site_url('dosen/pengampu') ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Dosen
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= session('success') ?></div>
    <?php endif; ?>
    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?></div>
    <?php endif; ?>

    <!-- Form Penambahan Mata Kuliah Diampu -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Penugasan Mengajar (Kaprodi)</h6>
        </div>
        <div class="card-body">
            <form method="post" action="<?= site_url('dosen/pengampu/store') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="dosen_id" value="<?= $dosen['id'] ?>">
                
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select bg-light" required>
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>>
                                    <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                        <select name="kategori" id="kategoriMk" class="form-select" onchange="toggleMkInput()" required>
                            <?php foreach ($kategori as $k => $l): ?>
                                <option value="<?= $k ?>"><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Input Dropdown: Mengajar di PS Sendiri -->
                    <div class="col-md-4" id="divMkDropdown">
                        <label class="form-label fw-semibold">Pilih Mata Kuliah Kurikulum <span class="text-danger">*</span></label>
                        <select name="mata_kuliah_id" id="mkDropdown" class="form-select">
                            <option value="">-- Pilih Mata Kuliah --</option>
                            <?php foreach ($mkList as $mk): ?>
                                <option value="<?= $mk['id'] ?>">
                                    [Smt <?= $mk['semester'] ?>] <?= esc($mk['kode']) ?> - <?= esc($mk['nama']) ?> (<?= $mk['sks'] ?? (($mk['sks_teori'] ?? 0) + ($mk['sks_praktek'] ?? 0) + ($mk['sks_lapangan'] ?? 0)) ?> SKS)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Input Manual: Mengajar di Luar PS -->
                    <div class="col-md-3" id="divMkManual" style="display: none;">
                        <label class="form-label fw-semibold">Nama Mata Kuliah <span class="text-danger">*</span></label>
                        <input type="text" name="nama_mk" id="namaMkManual" class="form-control" placeholder="Ketik nama MK">
                    </div>
                    
                    <div class="col-md-1" id="divSksManual" style="display: none;">
                        <label class="form-label fw-semibold">SKS <span class="text-danger">*</span></label>
                        <input type="number" name="sks" id="sksManual" class="form-control" min="1" step="0.5">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Jml Kelas</label>
                        <input type="number" name="jumlah_kelas" class="form-control" value="1" min="1" required>
                    </div>

                    <div class="col-md-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy me-1"></i> Simpan Penugasan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Daftar Penugasan -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover align-middle mb-0 text-center">
                <thead class="table-light">
                    <tr>
                        <th class="text-start ps-3">Mata Kuliah</th>
                        <th>Kategori</th>
                        <th>SKS</th>
                        <th>Kelas</th>
                        <th>Beban SKS</th>
                        <th class="text-end pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada penugasan mengajar di Tahun Akademik ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($list as $row): ?>
                            <tr>
                                <td class="text-start ps-3">
                                    <strong class="d-block text-dark"><?= esc($row['nama_mk']) ?></strong>
                                    <?php if ($row['kode_mk']): ?><small class="text-muted"><?= esc($row['kode_mk']) ?></small><?php endif; ?>
                                </td>
                                <td><span class="badge bg-info text-dark"><?= esc($kategori[$row['kategori']] ?? $row['kategori']) ?></span></td>
                                <td><?= $row['sks'] ?></td>
                                <td><?= $row['jumlah_kelas'] ?></td>
                                <td class="fw-bold text-success"><?= $row['sks'] * $row['jumlah_kelas'] ?></td>
                                <td class="text-end pe-3">
                                    <form action="<?= site_url("dosen/pengampu/{$row['id']}/delete") ?>" method="post" onsubmit="return confirm('Hapus penugasan ini?')" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleMkInput() {
    const kategori = document.getElementById('kategoriMk').value;
    const isPsSendiri = (kategori === 'ps_sendiri');
    
    document.getElementById('divMkDropdown').style.display = isPsSendiri ? 'block' : 'none';
    document.getElementById('divMkManual').style.display   = isPsSendiri ? 'none' : 'block';
    document.getElementById('divSksManual').style.display  = isPsSendiri ? 'none' : 'block';
    
    document.getElementById('mkDropdown').required   = isPsSendiri;
    document.getElementById('namaMkManual').required = !isPsSendiri;
    document.getElementById('sksManual').required    = !isPsSendiri;
}

document.addEventListener('DOMContentLoaded', toggleMkInput);
</script>
<?= $this->endSection() ?>