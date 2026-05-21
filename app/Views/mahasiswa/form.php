<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($mhs) ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-person-plus"></i> <?= $isEdit ? 'Edit Data Mahasiswa' : 'Tambah Mahasiswa' ?></h4>
    <a href="<?= site_url('mahasiswa') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<form method="post" action="<?= esc($action) ?>">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white fw-semibold">Data Pokok</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" class="form-select" required>
                                <option value="">— Pilih —</option>
                                <?php foreach ($prodiList as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= old('prodi_id', $mhs['prodi_id'] ?? session('active_prodi_id')) == $p['id'] ? 'selected' : '' ?>>
                                        <?= esc($p['nama']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control" required maxlength="30"
                                value="<?= esc(old('nim', $mhs['nim'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?= esc(old('nama', $mhs['nama'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">JK</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="">—</option>
                                <option value="L" <?= old('jenis_kelamin', $mhs['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>L</option>
                                <option value="P" <?= old('jenis_kelamin', $mhs['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>P</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                value="<?= esc(old('tempat_lahir', $mhs['tempat_lahir'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                value="<?= esc(old('tanggal_lahir', $mhs['tanggal_lahir'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= esc(old('email', $mhs['email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="<?= esc(old('no_hp', $mhs['no_hp'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kota Asal</label>
                            <input type="text" name="kota_asal" class="form-control"
                                value="<?= esc(old('kota_asal', $mhs['kota_asal'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Provinsi Asal</label>
                            <input type="text" name="provinsi_asal" class="form-control"
                                value="<?= esc(old('provinsi_asal', $mhs['provinsi_asal'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header bg-success text-white fw-semibold">Status & Akademik</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tahun Masuk</label>
                            <select name="tahun_masuk_id" class="form-select">
                                <option value="">—</option>
                                <?php foreach ($taList as $ta): ?>
                                    <option value="<?= $ta['id'] ?>" <?= old('tahun_masuk_id', $mhs['tahun_masuk_id'] ?? '') == $ta['id'] ? 'selected' : '' ?>>
                                        <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jalur Masuk</label>
                            <input type="text" name="jalur_masuk" class="form-control" placeholder="SNBP, SNBT, Mandiri..."
                                value="<?= esc(old('jalur_masuk', $mhs['jalur_masuk'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" id="statusField" onchange="toggleLulusFields()">
                                <?php foreach (['aktif','lulus','cuti','do','keluar'] as $s): ?>
                                    <option value="<?= $s ?>" <?= old('status', $mhs['status'] ?? 'aktif') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4" id="groupTglLulus">
                            <label class="form-label">Tanggal Lulus</label>
                            <input type="date" name="tgl_lulus" class="form-control"
                                value="<?= esc(old('tgl_lulus', $mhs['tgl_lulus'] ?? '')) ?>">
                        </div>
                        <div class="col-md-2" id="groupIpk">
                            <label class="form-label">IPK</label>
                            <input type="number" name="ipk" class="form-control" min="0" max="4" step="0.01"
                                value="<?= esc(old('ipk', $mhs['ipk'] ?? '')) ?>">
                        </div>
                        <div class="col-md-2" id="groupMasaStudi">
                            <label class="form-label">Masa Studi (bln)</label>
                            <input type="number" name="masa_studi_bulan" class="form-control" min="1"
                                value="<?= esc(old('masa_studi_bulan', $mhs['masa_studi_bulan'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card bg-light border-primary">
                <div class="card-body small">
                    <div class="fw-semibold mb-2 text-primary"><i class="bi bi-info-circle"></i> Catatan LAM INFOKOM</div>
                    <ul class="mb-0 ps-3">
                        <li>Tabel <strong>2.A.1</strong>: perkembangan mahasiswa per tahun masuk.</li>
                        <li>Tabel <strong>2.A.2</strong>: mahasiswa asing (jika ada).</li>
                        <li>Tabel <strong>2.A.3</strong>: evaluasi capaian pembelajaran.</li>
                        <li><strong>IPK</strong> dan <strong>masa studi</strong> lulusan wajib diisi untuk butir C2.14 & C2.15.</li>
                        <li>Setelah mahasiswa lulus, isi <a href="<?= site_url('mahasiswa/tracer') ?>">Tracer Study</a> untuk butir C2.16–C2.17.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-floppy"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Mahasiswa' ?>
        </button>
        <a href="<?= site_url('mahasiswa') ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<script>
function toggleLulusFields() {
    const isLulus = document.getElementById('statusField').value === 'lulus';
    ['groupTglLulus','groupIpk','groupMasaStudi'].forEach(id => {
        document.getElementById(id).style.opacity = isLulus ? '1' : '0.4';
    });
}
document.addEventListener('DOMContentLoaded', toggleLulusFields);
</script>

<?= $this->endSection() ?>
