<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-graph-up-arrow"></i> Input Tracer Study — <?= esc($mhs['nama']) ?></h4>
    <a href="<?= site_url('mahasiswa/tracer') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show"><?= esc(session('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif ?>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white fw-semibold">Profil Lulusan</div>
            <div class="card-body small">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted pe-3">NIM</th><td><code><?= esc($mhs['nim']) ?></code></td></tr>
                    <tr><th class="text-muted">Nama</th><td><?= esc($mhs['nama']) ?></td></tr>
                    <tr><th class="text-muted">Tgl Lulus</th><td><?= $mhs['tgl_lulus'] ? date('d M Y', strtotime($mhs['tgl_lulus'])) : '-' ?></td></tr>
                    <tr><th class="text-muted">IPK</th><td><?= $mhs['ipk'] ? number_format($mhs['ipk'],2) : '-' ?></td></tr>
                    <tr><th class="text-muted">Masa Studi</th><td><?= $mhs['masa_studi_bulan'] ? $mhs['masa_studi_bulan'].' bulan' : '-' ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3 bg-light border-info">
            <div class="card-body small">
                <div class="fw-semibold mb-1 text-info"><i class="bi bi-info-circle"></i> Ambang Skor LAM C2</div>
                <table class="table table-sm table-borderless mb-0">
                    <tr><th>Masa Tunggu</th><td></td></tr>
                    <tr class="table-success"><td class="ps-3">< 3 bulan</td><td>Skor 4</td></tr>
                    <tr><td class="ps-3">< 6 bulan</td><td>Skor 3</td></tr>
                    <tr><td class="ps-3">< 12 bulan</td><td>Skor 2</td></tr>
                    <tr class="table-danger"><td class="ps-3">≥ 12 bulan</td><td>Skor 1</td></tr>
                    <tr><th>Kesesuaian Bidang</th><td></td></tr>
                    <tr class="table-success"><td class="ps-3">≥ 80% sesuai</td><td>Skor 4</td></tr>
                    <tr><td class="ps-3">≥ 60%</td><td>Skor 3</td></tr>
                    <tr class="table-danger"><td class="ps-3">< 40%</td><td>Skor 1</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Form Tracer Study</div>
            <div class="card-body">
                <form method="post" action="<?= site_url("mahasiswa/tracer/{$mhs['id']}/store") ?>">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Isi</label>
                            <input type="date" name="tgl_isi" class="form-control"
                                value="<?= esc(old('tgl_isi', $tracer['tgl_isi'] ?? date('Y-m-d'))) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Masa Tunggu Kerja (bulan)</label>
                            <input type="number" name="masa_tunggu_bulan" class="form-control" min="0"
                                placeholder="mis. 2"
                                value="<?= esc(old('masa_tunggu_bulan', $tracer['masa_tunggu_bulan'] ?? '')) ?>">
                            <div class="form-text">0 = langsung bekerja sebelum wisuda</div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kesesuaian Bidang Kerja</label>
                            <select name="kesesuaian_bidang_kerja" class="form-select">
                                <option value="">— Pilih —</option>
                                <?php foreach (['sangat_sesuai','sesuai','kurang_sesuai','tidak_sesuai'] as $k): ?>
                                    <option value="<?= $k ?>" <?= old('kesesuaian_bidang_kerja', $tracer['kesesuaian_bidang_kerja'] ?? '') === $k ? 'selected' : '' ?>>
                                        <?= ucwords(str_replace('_',' ', $k)) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tingkat Pekerjaan</label>
                            <select name="tingkat_pekerjaan" class="form-select">
                                <option value="">— Pilih —</option>
                                <?php foreach (['lokal','nasional','multinasional','internasional','wirausaha'] as $tp): ?>
                                    <option value="<?= $tp ?>" <?= old('tingkat_pekerjaan', $tracer['tingkat_pekerjaan'] ?? '') === $tp ? 'selected' : '' ?>>
                                        <?= ucfirst($tp) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Perusahaan / Instansi</label>
                            <input type="text" name="nama_perusahaan" class="form-control"
                                value="<?= esc(old('nama_perusahaan', $tracer['nama_perusahaan'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jabatan / Posisi</label>
                            <input type="text" name="jabatan" class="form-control"
                                value="<?= esc(old('jabatan', $tracer['jabatan'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gaji Pertama (Rp)</label>
                            <input type="text" name="gaji_pertama" class="form-control" placeholder="mis. 4500000"
                                value="<?= esc(old('gaji_pertama', $tracer['gaji_pertama'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="lanjut_studi" class="form-check-input" id="lanjutStudi" value="1"
                                    <?= old('lanjut_studi', $tracer['lanjut_studi'] ?? 0) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="lanjutStudi">Lulusan melanjutkan studi (S2/profesi)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-floppy"></i> Simpan Tracer Study
                        </button>
                        <a href="<?= site_url('mahasiswa/tracer') ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
