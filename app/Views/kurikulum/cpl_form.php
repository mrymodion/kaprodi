<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $isEdit = !empty($cpl) ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-award"></i> <?= $isEdit ? 'Edit CPL' : 'Tambah CPL' ?> — <?= esc($kuri['kode']) ?></h4>
    <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-cpl") ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<?php if (session('errors')): ?>
    <div class="alert alert-danger"><ul class="mb-0"><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Data CPL</div>
            <div class="card-body">
                <form method="post" action="<?= esc($action) ?>">
                    <?= csrf_field() ?>
                    <?php if ($isEdit): ?><input type="hidden" name="_method" value="PUT"><?php endif ?>
                    <input type="hidden" name="kurikulum_id" value="<?= $kuri['id'] ?>">

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode CPL <span class="text-danger">*</span></label>
                            <input type="text" name="kode" class="form-control" required maxlength="20"
                                placeholder="mis. CPL-01"
                                value="<?= esc(old('kode', $cpl['kode'] ?? '')) ?>">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Aspek <span class="text-danger">*</span></label>
                            <select name="aspek" class="form-select" required>
                                <option value="">— Pilih —</option>
                                <?php foreach ([
                                    'sikap'               => 'Sikap',
                                    'pengetahuan'         => 'Pengetahuan',
                                    'keterampilan_umum'   => 'Keterampilan Umum',
                                    'keterampilan_khusus' => 'Keterampilan Khusus',
                                ] as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= old('aspek', $cpl['aspek'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Deskripsi CPL <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" class="form-control" rows="5" required
                            placeholder="Mampu ..."><?= esc(old('deskripsi', $cpl['deskripsi'] ?? '')) ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Simpan CPL</button>
                        <a href="<?= site_url("kurikulum/{$kuri['id']}#tab-cpl") ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card bg-light border-success">
            <div class="card-body small">
                <div class="fw-semibold mb-2 text-success"><i class="bi bi-info-circle"></i> Panduan CPL SN-Dikti</div>
                <p>CPL program D3 minimal mencakup 4 aspek:</p>
                <ul>
                    <li><strong>Sikap</strong> (S): mengikuti SN-Dikti, minimal 10 butir.</li>
                    <li><strong>Pengetahuan</strong> (P): spesifik bidang prodi.</li>
                    <li><strong>Keterampilan Umum</strong> (KU): mengikuti SN-Dikti.</li>
                    <li><strong>Keterampilan Khusus</strong> (KK): khas prodi, minimal 3 butir.</li>
                </ul>
                <p class="mb-0">Kode lazim: <code>S-01, P-01, KU-01, KK-01</code> dst.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
