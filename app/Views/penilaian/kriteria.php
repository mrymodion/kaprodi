<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Nilai Kriteria <?= esc($kriteria['kode']) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-dark">
                <i class="bi bi-pencil-square text-primary me-2"></i> Input Skor: Kriteria <?= esc($kriteria['kode']) ?>
            </h1>
            <p class="text-muted mb-0"><?= esc($kriteria['nama']) ?></p>
        </div>
        <a href="<?= site_url('penilaian') ?><?= $taId ? '?ta_id=' . $taId : '' ?>" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Rekap
        </a>
    </div>

    <?php if (session('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= esc(session('success')) ?></div>
    <?php endif ?>

    <div class="accordion" id="accordionPenilaian">
        <?php foreach ($butir as $i => $b): 
            $skor = $skorData[$b['id']] ?? [];
            $rubs = $rubrik[$b['id']] ?? [];
            $skorFinal = $skor['skor_final'] ?? null;
            $isDone = $skorFinal !== null;
            $badgeColor = $isDone ? ($skorFinal >= 3 ? 'bg-success' : 'bg-warning text-dark') : 'bg-secondary';
        ?>
            <div class="accordion-item mb-3 border-0 shadow-sm rounded-3 overflow-hidden">
                <h2 class="accordion-header" id="heading<?= $b['id'] ?>">
                    <button class="accordion-button <?= $isDone ? 'collapsed' : '' ?> bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $b['id'] ?>">
                        <div class="d-flex justify-content-between w-100 align-items-center pe-3">
                            <div>
                                <strong class="text-primary me-2"><?= esc($b['kode']) ?></strong> 
                                <span class="text-dark"><?= esc($b['judul']) ?></span>
                            </div>
                            <span class="badge <?= $badgeColor ?> fs-6 rounded-pill px-3">
                                Skor: <?= $isDone ? number_format($skorFinal, 2) : 'Belum' ?>
                            </span>
                        </div>
                    </button>
                </h2>
                
                <div id="collapse<?= $b['id'] ?>" class="accordion-collapse collapse <?= !$isDone && $i === 0 ? 'show' : '' ?>" data-bs-parent="#accordionPenilaian">
                    <div class="accordion-body bg-light border-top">
                        <div class="row">
                            
                            <!-- Panel Kiri: Rubrik Penilaian -->
                            <div class="col-lg-8">
                                <div class="mb-3">
                                    <span class="badge bg-secondary mb-2"><?= strtoupper($b['jenis_ipo']) ?></span>
                                    <span class="badge bg-info text-dark mb-2">Bobot: <?= $b['bobot'] ?></span>
                                    <p class="text-dark mb-3"><?= esc($b['deskripsi']) ?></p>
                                </div>

                                <h6 class="fw-bold text-muted mb-2"><i class="bi bi-table me-1"></i> Matriks Rubrik Penilaian</h6>
                                <ul class="list-group shadow-sm mb-4">
                                    <?php foreach ([4, 3, 2, 1] as $lvl): ?>
                                        <li class="list-group-item d-flex align-items-start <?= ($skorFinal !== null && round($skorFinal) == $lvl) ? 'bg-primary bg-opacity-10 border-primary' : '' ?>">
                                            <div class="me-3 mt-1">
                                                <span class="badge <?= $lvl >= 3 ? 'bg-success' : 'bg-danger' ?> rounded-circle p-2 fs-6"><?= $lvl ?></span>
                                            </div>
                                            <div class="text-muted small">
                                                <?= esc($rubs[$lvl]['deskriptor'] ?? 'Belum ada deskriptor untuk level ini di instrumen.') ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>

                            <!-- Panel Kanan: Form Input Skor -->
                            <div class="col-lg-4 border-start">
                                <div class="bg-white p-4 rounded-3 shadow-sm h-100">
                                    <form action="<?= site_url('penilaian/skor/' . $b['id'] . '/save') ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="ta_id" value="<?= esc($taId ?? '') ?>">
                                        
                                        <?php if (($skor['skor_auto'] ?? null) !== null): ?>
                                            <div class="alert alert-info py-2 px-3 small border-0 d-flex align-items-center mb-3">
                                                <i class="bi bi-robot fs-4 me-2 text-primary"></i>
                                                <div>
                                                    <strong class="d-block">Auto-Skor Sistem: <?= number_format((float) $skor['skor_auto'], 2) ?></strong>
                                                    <em>Data kuantitatif dihitung dari database.</em>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Skor Manual (Asesor)</label>
                                            <input type="number" step="0.01" min="0" max="4" name="skor_manual" class="form-control form-control-lg text-center fs-3 fw-bold text-primary" placeholder="0.00" value="<?= $skor['skor_manual'] ?? '' ?>">
                                            <div class="form-text small">Kosongkan jika ingin menggunakan Auto-Skor sistem (jika ada).</div>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label fw-bold text-dark">Catatan / Justifikasi</label>
                                            <textarea name="catatan" class="form-control" rows="4" placeholder="Tuliskan justifikasi pemberian skor..."><?= esc($skor['catatan'] ?? '') ?></textarea>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary fw-bold py-2 shadow-sm">
                                                <i class="bi bi-floppy me-1"></i> Simpan Penilaian
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>