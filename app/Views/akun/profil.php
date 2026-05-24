<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Profil Saya</h4>
    <a href="<?= site_url('akun') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card"><div class="card-body">
<form method="post" action="<?= site_url('akun/profil') ?>">
    <?= csrf_field() ?>

    <div class="alert alert-light border small mb-3">
        <i class="bi bi-lock"></i> NIDN, program studi, dan status keaktifan dikelola oleh prodi dan tidak dapat diubah di sini.
        Hubungi kaprodi bila ada koreksi.
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">Nama (tanpa gelar)</label>
            <input type="text" class="form-control" value="<?= esc($dosen['nama']) ?>" disabled>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Gelar Depan</label>
            <input type="text" name="gelar_depan" class="form-control" value="<?= esc(old('gelar_depan', $dosen['gelar_depan'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Gelar Belakang</label>
            <input type="text" name="gelar_belakang" class="form-control" value="<?= esc(old('gelar_belakang', $dosen['gelar_belakang'] ?? '')) ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-select">
                <?php foreach (['' => '—', 'L' => 'Laki-laki', 'P' => 'Perempuan'] as $v => $l): ?>
                <option value="<?= $v ?>" <?= old('jenis_kelamin', $dosen['jenis_kelamin'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-5">
            <label class="form-label fw-semibold">Tempat Lahir</label>
            <input type="text" name="tempat_lahir" class="form-control" value="<?= esc(old('tempat_lahir', $dosen['tempat_lahir'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="<?= esc(old('tanggal_lahir', $dosen['tanggal_lahir'] ?? '')) ?>">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc(old('email', $dosen['email'] ?? '')) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">No. HP</label>
            <input type="text" name="no_hp" class="form-control" value="<?= esc(old('no_hp', $dosen['no_hp'] ?? '')) ?>">
        </div>

        <div class="col-md-3">
            <label class="form-label fw-semibold">Pendidikan Terakhir</label>
            <select name="pendidikan_terakhir" class="form-select">
                <?php foreach (['' => '—', 'S1' => 'S1', 'S2' => 'S2', 'S3' => 'S3', 'Sp' => 'Sp', 'Sp2' => 'Sp2'] as $v => $l): ?>
                <option value="<?= $v ?>" <?= old('pendidikan_terakhir', $dosen['pendidikan_terakhir'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-9">
            <label class="form-label fw-semibold">Bidang Keahlian</label>
            <input type="text" name="bidang_keahlian" class="form-control" value="<?= esc(old('bidang_keahlian', $dosen['bidang_keahlian'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-semibold">Jabatan Akademik</label>
            <select name="jabatan_akademik" class="form-select">
                <?php foreach (['' => '—', 'Tenaga Pengajar' => 'Tenaga Pengajar', 'Asisten Ahli' => 'Asisten Ahli', 'Lektor' => 'Lektor', 'Lektor Kepala' => 'Lektor Kepala', 'Guru Besar' => 'Guru Besar'] as $v => $l): ?>
                <option value="<?= $v ?>" <?= old('jabatan_akademik', $dosen['jabatan_akademik'] ?? '') === $v ? 'selected' : '' ?>><?= $l ?></option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">No. SK Jabatan</label>
            <input type="text" name="no_sk_jad" class="form-control" value="<?= esc(old('no_sk_jad', $dosen['no_sk_jad'] ?? '')) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Tgl SK Jabatan</label>
            <input type="date" name="tgl_sk_jad" class="form-control" value="<?= esc(old('tgl_sk_jad', $dosen['tgl_sk_jad'] ?? '')) ?>">
        </div>

        <div class="col-md-4">
            <div class="form-check form-switch mt-4">
                <input class="form-check-input" type="checkbox" name="sertifikat_pendidik" id="serdos" value="1" <?= old('sertifikat_pendidik', $dosen['sertifikat_pendidik'] ?? 0) ? 'checked' : '' ?>>
                <label class="form-check-label" for="serdos">Bersertifikat Pendidik</label>
            </div>
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold">No. Sertifikat Pendidik</label>
            <input type="text" name="no_serdos" class="form-control" value="<?= esc(old('no_serdos', $dosen['no_serdos'] ?? '')) ?>">
        </div>

        <div class="col-12"><hr class="my-1"><span class="text-muted small">ID Profil Penelitian</span></div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">ORCID</label>
            <input type="text" name="orcid" class="form-control" placeholder="0000-0000-0000-0000" value="<?= esc(old('orcid', $dosen['orcid'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">SINTA ID</label>
            <input type="text" name="sinta_id" class="form-control" value="<?= esc(old('sinta_id', $dosen['sinta_id'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Scopus ID</label>
            <input type="text" name="scopus_id" class="form-control" value="<?= esc(old('scopus_id', $dosen['scopus_id'] ?? '')) ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold">Google Scholar ID</label>
            <input type="text" name="google_scholar_id" class="form-control" value="<?= esc(old('google_scholar_id', $dosen['google_scholar_id'] ?? '')) ?>">
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
        <a href="<?= site_url('akun') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
    </div>
</form>
</div></div>

<?= $this->endSection() ?>
