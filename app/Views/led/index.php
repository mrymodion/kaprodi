<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Generator LED & AI<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-file-earmark-word text-primary me-2"></i> Laporan Evaluasi Diri (LED)
        </h1>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?></div>
    <?php endif; ?>
    <?php if (session()->has('success')): ?>
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i> <?= session('success') ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Panel Kiri: Daftar Paragraf Narasi -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary"><i class="bi bi-pencil-square me-1"></i> Editor Narasi LED</h6>
                    <span class="badge bg-light text-dark border">Groq AI Llama-3 Terintegrasi</span>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionLed">
                        <?php $i = 0; foreach ($grouped as $kriteria => $templates): ?>
                            <div class="accordion-item mb-2 border rounded">
                                <h2 class="accordion-header" id="heading<?= $i ?>">
                                    <button class="accordion-button <?= $i === 0 ? '' : 'collapsed' ?> bg-light text-dark fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i ?>">
                                        <?= esc($kriteria) ?>
                                    </button>
                                </h2>
                                <div id="collapse<?= $i ?>" class="accordion-collapse collapse <?= $i === 0 ? 'show' : '' ?>" data-bs-parent="#accordionLed">
                                    <div class="accordion-body bg-white">
                                        <?php foreach ($templates as $t): ?>
                                            <div class="mb-4 pb-4 <?= $t !== end($templates) ? 'border-bottom' : '' ?>">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="fw-bold mb-0 text-dark"><?= esc($t['judul']) ?></h6>
                                                    <?php if ($t['generated_by_ai']): ?>
                                                        <span class="badge bg-info text-dark shadow-sm"><i class="bi bi-robot"></i> AI Generated</span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <form action="<?= site_url('led/narasi/'.$t['id'].'/save') ?>" method="POST" class="mb-2">
                                                    <?= csrf_field() ?>
                                                    <textarea name="isi" class="form-control mb-2" rows="6" placeholder="Ketik narasi di sini atau biarkan AI yang menulis..."><?= esc($t['narasi'] ?? '') ?></textarea>
                                                    <button type="submit" class="btn btn-sm btn-primary px-4 shadow-sm"><i class="bi bi-save"></i> Simpan Manual</button>
                                                </form>

                                                <form action="<?= site_url('led/groq/'.$t['id']) ?>" method="POST" onsubmit="showLoading(this)">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-dark fw-semibold ai-btn w-100 mt-2">
                                                        <i class="bi bi-magic text-warning me-1"></i> Buat Narasi Otomatis dengan AI
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php $i++; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Kanan: Render & Download -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 border-start border-4 border-primary">
                <div class="card-body">
                    <h6 class="fw-bold mb-3"><i class="bi bi-gear-fill me-1"></i> Render ke Word</h6>
                    <p class="text-muted small">
                        Sistem akan menyusun seluruh narasi di atas menjadi dokumen Word <code>.docx</code> lengkap dengan halaman cover.
                    </p>
                    <form action="<?= site_url('led/generate') ?>" method="POST">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                            <i class="bi bi-file-word"></i> Generate Dokumen LED
                        </button>
                    </form>
                </div>
            </div>

            <!-- Daftar Riwayat Download -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold"><i class="bi bi-clock-history me-1"></i> Riwayat Render</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <?php if (empty($files)): ?>
                            <li class="list-group-item text-muted text-center py-4 small">Belum ada dokumen dirender.</li>
                        <?php else: ?>
                            <?php foreach ($files as $f): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="text-truncate me-2" style="max-width: 200px;">
                                        <i class="bi bi-file-earmark-word text-primary me-2"></i>
                                        <a href="<?= site_url('led/download/' . rawurlencode($f['name'])) ?>" class="text-decoration-none fw-semibold small" target="_blank" title="Unduh">
                                            <?= esc($f['name']) ?>
                                        </a>
                                        <br><small class="text-muted" style="font-size: 0.7rem;"><?= date('d M Y, H:i', $f['mtime']) ?></small>
                                    </div>
                                    <span class="badge bg-light text-dark border"><?= round($f['size'] / 1024) ?> KB</span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showLoading(form) {
    const btn = form.querySelector('.ai-btn');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span class="fst-italic text-muted">AI sedang berpikir (± 15 detik)...</span>';
    
    // Timeout failsafe reset
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    }, 30000);
}
</script>
<?= $this->endSection() ?>