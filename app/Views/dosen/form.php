<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($dosen) ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">
        <i class="bi bi-person-badge"></i>
        <?= $isEdit ? 'Edit Data Dosen' : 'Tambah Dosen Baru' ?>
    </h4>
    <a href="<?= site_url('dosen') ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach ?>
        </ul>
    </div>
<?php endif ?>

<form method="post" action="<?= esc($action) ?>" id="dosenForm">
    <?= csrf_field() ?>
    <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>

    <div class="row g-4">
        <!-- Kiri: Data Pokok -->
        <div class="col-md-7">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white fw-semibold">Data Pokok</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Prodi <span class="text-danger">*</span></label>
                            <select name="prodi_id" class="form-select" required>
                                <option value="">— Pilih Prodi —</option>
                                <?php foreach ($prodiList as $p): ?>
                                    <option value="<?= $p['id'] ?>" <?= old('prodi_id', $dosen['prodi_id'] ?? session('active_prodi_id')) == $p['id'] ? 'selected' : '' ?>>
                                        <?= esc($p['nama']) ?> (<?= esc($p['jenjang']) ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIDN</label>
                            <input type="text" name="nidn" class="form-control" maxlength="20"
                                value="<?= esc(old('nidn', $dosen['nidn'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip" class="form-control" maxlength="30"
                                value="<?= esc(old('nip', $dosen['nip'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gelar Depan</label>
                            <input type="text" name="gelar_depan" class="form-control" placeholder="Prof. Dr."
                                value="<?= esc(old('gelar_depan', $dosen['gelar_depan'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?= esc(old('nama', $dosen['nama'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Gelar Belakang</label>
                            <input type="text" name="gelar_belakang" class="form-control" placeholder="M.Kom."
                                value="<?= esc(old('gelar_belakang', $dosen['gelar_belakang'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select">
                                <option value="">—</option>
                                <option value="L" <?= old('jenis_kelamin', $dosen['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= old('jenis_kelamin', $dosen['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control"
                                value="<?= esc(old('tempat_lahir', $dosen['tempat_lahir'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control"
                                value="<?= esc(old('tanggal_lahir', $dosen['tanggal_lahir'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?= esc(old('email', $dosen['email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No HP</label>
                            <input type="text" name="no_hp" class="form-control"
                                value="<?= esc(old('no_hp', $dosen['no_hp'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Bidang Keahlian</label>
                            <input type="text" name="bidang_keahlian" class="form-control"
                                placeholder="mis. Kecerdasan Buatan, Jaringan Komputer"
                                value="<?= esc(old('bidang_keahlian', $dosen['bidang_keahlian'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Identifikasi Akademik -->
            <div class="card mb-3">
                <div class="card-header bg-success text-white fw-semibold">Identifikasi Akademik</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pend. Terakhir</label>
                            <select name="pendidikan_terakhir" class="form-select">
                                <option value="">—</option>
                                <?php foreach (['S1','S2','S3','Sp','Sp2'] as $pd): ?>
                                    <option value="<?= $pd ?>" <?= old('pendidikan_terakhir', $dosen['pendidikan_terakhir'] ?? '') === $pd ? 'selected' : '' ?>><?= $pd ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Linieritas</label>
                            <select name="linieritas" class="form-select">
                                <option value="linier" <?= old('linieritas', $dosen['linieritas'] ?? 'linier') === 'linier' ? 'selected' : '' ?>>Linier</option>
                                <option value="tidak_linier" <?= old('linieritas', $dosen['linieritas'] ?? '') === 'tidak_linier' ? 'selected' : '' ?>>Tidak Linier</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Kepegawaian</label>
                            <select name="status_kepegawaian" class="form-select">
                                <option value="tetap" <?= old('status_kepegawaian', $dosen['status_kepegawaian'] ?? 'tetap') === 'tetap' ? 'selected' : '' ?>>Tetap</option>
                                <option value="tidak_tetap" <?= old('status_kepegawaian', $dosen['status_kepegawaian'] ?? '') === 'tidak_tetap' ? 'selected' : '' ?>>Tidak Tetap</option>
                                <option value="dosen_industri" <?= old('status_kepegawaian', $dosen['status_kepegawaian'] ?? '') === 'dosen_industri' ? 'selected' : '' ?>>Dosen Industri</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan Akademik</label>
                            <select name="jabatan_akademik" class="form-select">
                                <option value="">—</option>
                                <?php foreach (['Tenaga Pengajar','Asisten Ahli','Lektor','Lektor Kepala','Guru Besar'] as $jad): ?>
                                    <option value="<?= $jad ?>" <?= old('jabatan_akademik', $dosen['jabatan_akademik'] ?? '') === $jad ? 'selected' : '' ?>><?= $jad ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">No SK JAD</label>
                            <input type="text" name="no_sk_jad" class="form-control"
                                value="<?= esc(old('no_sk_jad', $dosen['no_sk_jad'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tgl SK JAD</label>
                            <input type="date" name="tgl_sk_jad" class="form-control"
                                value="<?= esc(old('tgl_sk_jad', $dosen['tgl_sk_jad'] ?? '')) ?>">
                        </div>
                        <div class="col-md-8 d-flex align-items-end gap-3">
                            <div class="form-check mb-0 mt-3">
                                <input class="form-check-input" type="checkbox" name="sertifikat_pendidik" id="serdos" value="1"
                                    <?= old('sertifikat_pendidik', $dosen['sertifikat_pendidik'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="serdos">Bersertifikat Pendidik (Serdos)</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">No Serdos</label>
                            <input type="text" name="no_serdos" class="form-control"
                                value="<?= esc(old('no_serdos', $dosen['no_serdos'] ?? '')) ?>">
                        </div>
                        <?php if ($isEdit): ?>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="aktif" class="form-select">
                                <option value="1" <?= ($dosen['aktif'] ?? 1) ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= !($dosen['aktif'] ?? 1) ? 'selected' : '' ?>>Non-aktif</option>
                            </select>
                        </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: ID Penelitian -->
        <div class="col-md-5">
            <div class="card mb-3">
                <div class="card-header bg-info text-white fw-semibold">ID Penelitian / Publikasi</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-journal-text text-warning"></i> SINTA ID</label>
                            <input type="text" name="sinta_id" class="form-control" placeholder="12345"
                                value="<?= esc(old('sinta_id', $dosen['sinta_id'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-circle-fill text-warning"></i> Scopus Author ID</label>
                            <input type="text" name="scopus_id" class="form-control"
                                value="<?= esc(old('scopus_id', $dosen['scopus_id'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-search"></i> Google Scholar ID</label>
                            <input type="text" name="google_scholar_id" class="form-control"
                                value="<?= esc(old('google_scholar_id', $dosen['google_scholar_id'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label"><i class="bi bi-link"></i> ORCID</label>
                            <input type="text" name="orcid" class="form-control" placeholder="0000-0000-0000-0000"
                                value="<?= esc(old('orcid', $dosen['orcid'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-light border-primary">
                <div class="card-body small">
                    <div class="fw-semibold mb-2 text-primary"><i class="bi bi-info-circle"></i> Catatan LAM INFOKOM</div>
                    <ul class="mb-0 ps-3">
                        <li>Minimal <strong>5 DTPR aktif</strong> per prodi.</li>
                        <li>Target <strong>≥ 30% S3</strong> untuk skor maksimal butir C1.</li>
                        <li>Target <strong>≥ 30% Lektor Kepala / Guru Besar</strong>.</li>
                        <li>Target <strong>≥ 80% linier</strong> dengan prodi.</li>
                        <li>EWMP diisi per semester di menu <a href="<?= site_url('dosen/ewmp') ?>">Input EWMP</a>.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-floppy"></i> <?= $isEdit ? 'Simpan Perubahan' : 'Tambah Dosen' ?>
        </button>
        <a href="<?= site_url('dosen') ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>

<?= $this->endSection() ?>
