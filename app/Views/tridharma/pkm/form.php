<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($item); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-megaphone"></i> <?= $isEdit ? 'Edit' : 'Tambah' ?> PkM</h4>
    <a href="<?= $backUrl ?? site_url('pkm') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0">
    <?php foreach ((array)session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?>
</ul></div>
<?php endif ?>

<div class="card">
    <div class="card-body">
        <form method="post" action="<?= $action ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-semibold">Judul Kegiatan PkM <span class="text-danger">*</span></label>
                    <textarea name="judul" class="form-control" rows="3" required><?= esc(old('judul', $item['judul'] ?? '')) ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Ketua Pelaksana</label>
                    <?php if (!empty($lockKetua)): ?>
                        <input type="text" class="form-control" value="<?= esc($lockKetuaNama ?? 'Anda') ?>" disabled>
                    <?php else: ?>
                    <select name="ketua_dosen_id" class="form-select">
                        <option value="">— Pilih Dosen —</option>
                        <?php foreach ($dosen as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= old('ketua_dosen_id', $item['ketua_dosen_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                            <?= esc($d['nama']) ?> (<?= esc($d['nidn'] ?? '-') ?>)
                        </option>
                        <?php endforeach ?>
                    </select>
                    <?php endif ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tahun Akademik</label>
                    <select name="tahun_akademik_id" class="form-select">
                        <option value="">— Pilih TA —</option>
                        <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= old('tahun_akademik_id', $item['tahun_akademik_id'] ?? '') == $ta['id'] ? 'selected' : '' ?>>
                            <?= esc($ta['kode']) ?>
                        </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Sumber Dana</label>
                    <select name="sumber_dana" class="form-select" required>
                        <?php foreach (['pt_sendiri'=>'PT Sendiri','mandiri'=>'Mandiri','dalam_negeri'=>'Dalam Negeri','luar_negeri'=>'Luar Negeri'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= old('sumber_dana', $item['sumber_dana'] ?? 'pt_sendiri') === $v ? 'selected' : '' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Sumber Dana</label>
                    <input type="text" name="nama_sumber_dana" class="form-control" value="<?= esc(old('nama_sumber_dana', $item['nama_sumber_dana'] ?? '')) ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Jumlah Dana (Rp)</label>
                    <input type="number" name="jumlah_dana" class="form-control" min="0" value="<?= old('jumlah_dana', $item['jumlah_dana'] ?? '0') ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tahun Pelaksanaan</label>
                    <input type="number" name="tahun_pelaksanaan" class="form-control" min="2000" max="2099" placeholder="YYYY" value="<?= esc(old('tahun_pelaksanaan', $item['tahun_pelaksanaan'] ?? date('Y'))) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Mitra</label>
                    <input type="text" name="mitra" class="form-control" placeholder="Nama organisasi/instansi mitra" value="<?= esc(old('mitra', $item['mitra'] ?? '')) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi Kegiatan</label>
                    <input type="text" name="lokasi" class="form-control" value="<?= esc(old('lokasi', $item['lokasi'] ?? '')) ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Dampak / Luaran</label>
                    <button type="button" class="btn btn-sm btn-outline-info mb-1 ms-2 btn-ai-borang"
                        data-jenis="dampak_pkm" data-target="dampak" data-ctx='{"judul":"judul"}'><i class="bi bi-robot"></i> Bantu AI</button>
                    <textarea name="dampak" class="form-control" rows="2"><?= esc(old('dampak', $item['dampak'] ?? '')) ?></textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Anggota Pelaksana (Dosen)</label>
                    <div class="row g-2">
                        <?php foreach ($dosen as $d): ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="anggota[]" value="<?= $d['id'] ?>"
                                    id="ang_<?= $d['id'] ?>"
                                    <?= in_array($d['id'], $anggota) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="ang_<?= $d['id'] ?>"><?= esc($d['nama']) ?></label>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="libatkan_mahasiswa" id="libatMhs" value="1"
                            <?= old('libatkan_mahasiswa', $item['libatkan_mahasiswa'] ?? 0) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="libatMhs">Melibatkan Mahasiswa</label>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jumlah Mahasiswa Terlibat</label>
                    <input type="number" name="jumlah_mhs_terlibat" class="form-control" min="0" value="<?= old('jumlah_mhs_terlibat', $item['jumlah_mhs_terlibat'] ?? '0') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Bukti (PDF)</label>
                    <?php $bk = old('link_bukti', $item['link_bukti'] ?? ''); ?>
                    <?php if (!empty($bk)): ?>
                    <div class="mb-1 small">
                        <?php if (\App\Services\EvidenceStorage::isStoredFile($bk)): ?>
                            <a href="<?= site_url('eviden/file/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti</a>
                        <?php else: ?>
                            <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Bukti (link lama)</a>
                        <?php endif ?>
                        <label class="ms-2 text-danger"><input type="checkbox" name="hapus_link_bukti" value="1"> hapus</label>
                    </div>
                    <?php endif ?>
                    <input type="file" name="link_bukti" class="form-control" accept="application/pdf">
                    <small class="text-muted">Unggah PDF dokumen pendukung (maks <?= (int) env('storage.maxUploadMb', 20) ?> MB, dikompres bila Ghostscript ada).</small>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                <a href="<?= $backUrl ?? site_url('pkm') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= view("partials/ai_borang_script") ?>

<?= $this->endSection() ?>
