<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-emoji-smile"></i> Input Kepuasan — <?= esc($mhs['nama']) ?></h4>
    <a href="<?= site_url('mahasiswa/kepuasan') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <div class="card-body">
        <p class="text-muted small">Alumni: <strong><?= esc($mhs['nama']) ?></strong> (NIM <?= esc($mhs['nim']) ?>). Skala 1 = Kurang, 2 = Cukup, 3 = Baik, 4 = Sangat Baik.</p>
        <form method="post" action="<?= site_url("mahasiswa/kepuasan/{$mhs['id']}/store") ?>">
            <?= csrf_field() ?>
            <div class="row g-3 mb-2">
                <div class="col-md-4"><label class="form-label fw-semibold">Nama Atasan/Penilai</label>
                    <input type="text" name="nama_atasan" class="form-control" value="<?= esc($data['nama_atasan'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Jabatan</label>
                    <input type="text" name="jabatan_atasan" class="form-control" value="<?= esc($data['jabatan_atasan'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label fw-semibold">Perusahaan/Instansi</label>
                    <input type="text" name="nama_perusahaan" class="form-control" value="<?= esc($data['nama_perusahaan'] ?? '') ?>"></div>
            </div>

            <table class="table table-sm align-middle">
                <thead class="table-light"><tr><th>Aspek</th><th style="width:45%">Nilai (1–4)</th></tr></thead>
                <tbody>
                <?php foreach ($aspek as $key => $label): ?>
                    <tr>
                        <td><?= esc($label) ?></td>
                        <td>
                            <select name="<?= $key ?>" class="form-select form-select-sm">
                                <option value="">—</option>
                                <?php foreach ([4=>'4 · Sangat Baik',3=>'3 · Baik',2=>'2 · Cukup',1=>'1 · Kurang'] as $n=>$t): ?>
                                    <option value="<?= $n ?>" <?= (int)($data[$key] ?? 0) === $n ? 'selected' : '' ?>><?= $t ?></option>
                                <?php endforeach ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

            <div class="mb-3">
                <label class="form-label fw-semibold">Saran (opsional)</label>
                <textarea name="saran" class="form-control" rows="3"><?= esc($data['saran'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
            <a href="<?= site_url('mahasiswa/kepuasan') ?>" class="btn btn-outline-secondary ms-2">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
