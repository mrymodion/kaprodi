<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>Generator LKPS Excel<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-dark">
            <i class="bi bi-file-earmark-excel text-success me-2"></i> 
            Generator Laporan Kinerja (LKPS)
        </h1>
    </div>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session('error') ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8 col-xl-6">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-success"><i class="bi bi-cloud-download me-1"></i> Unduh Tabel LKPS</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-4">
                        Sistem akan mengumpulkan seluruh data (Kurikulum, Dosen, Mahasiswa, Publikasi, dsb.) secara <em>real-time</em> 
                        dan menyusunnya ke dalam format template Excel resmi sesuai instrumen akreditasi prodi Anda.
                    </p>

                    <form action="<?= site_url('lkps/download') ?>" method="GET">
                        
                        <div class="mb-4">
                            <label for="ta_id" class="form-label fw-bold small text-uppercase">1. Filter Tahun Akademik (Opsional)</label>
                            <select class="form-select" id="ta_id" name="ta_id">
                                <option value="">-- Semua Tahun Akademik (Akumulasi) --</option>
                                <?php foreach ($taList as $ta): ?>
                                    <option value="<?= $ta['id'] ?>"><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text mt-1">Kosongkan jika ingin menarik seluruh riwayat data historis.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-uppercase">2. Pilih Cakupan Kriteria</label>
                            
                            <!-- Opsi Khusus C2 -->
                            <label class="d-block mb-3">
                                <input type="radio" name="kriteria" value="C2" class="d-none peer" id="kriteria-c2">
                                <div class="card card-body border border-2 peer-checked-border-success cursor-pointer h-100">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-book fs-3 text-info me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Khusus Kriteria C2 (Relevansi Pendidikan)</h6>
                                            <p class="mb-0 small text-muted">Hanya generate sheet Kurikulum, CPL, Profil Lulusan, dan Tracer Study (2.A s.d. 2.D).</p>
                                        </div>
                                        <div class="ms-auto"><i class="bi bi-check-circle-fill text-success d-none check-icon fs-4"></i></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Opsi Khusus C3 -->
                            <label class="d-block mb-3">
                                <input type="radio" name="kriteria" value="C3" class="d-none peer" id="kriteria-c3">
                                <div class="card card-body border border-2 peer-checked-border-success cursor-pointer h-100">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-search fs-3 text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Khusus Kriteria C3 (Relevansi Penelitian)</h6>
                                            <p class="mb-0 small text-muted">Hanya generate sheet Penelitian, Kerjasama, Publikasi, dan HKI dari Penelitian DTPR.</p>
                                        </div>
                                        <div class="ms-auto"><i class="bi bi-check-circle-fill text-success d-none check-icon fs-4"></i></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Opsi Khusus C4 -->
                            <label class="d-block mb-3">
                                <input type="radio" name="kriteria" value="C4" class="d-none peer" id="kriteria-c4">
                                <div class="card card-body border border-2 peer-checked-border-success cursor-pointer h-100">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-megaphone fs-3 text-warning me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Khusus Kriteria C4 (Relevansi PkM)</h6>
                                            <p class="mb-0 small text-muted">Hanya generate sheet PkM, Kerjasama, Publikasi, dan HKI dari PkM DTPR.</p>
                                        </div>
                                        <div class="ms-auto"><i class="bi bi-check-circle-fill text-success d-none check-icon fs-4"></i></div>
                                    </div>
                                </div>
                            </label>

                            <!-- Opsi Full LKPS -->
                            <label class="d-block mb-2">
                                <input type="radio" name="kriteria" value="all" class="d-none peer" id="kriteria-all" checked>
                                <div class="card card-body border border-2 peer-checked-border-success cursor-pointer h-100">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-file-earmark-spreadsheet fs-3 text-success me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Seluruh Kriteria Instrumen (Full LKPS)</h6>
                                            <p class="mb-0 small text-muted">Generate seluruh bagian (Buku LKPS Lengkap) dari instrumen LAM yang aktif.</p>
                                        </div>
                                        <div class="ms-auto"><i class="bi bi-check-circle-fill text-success d-none check-icon fs-4"></i></div>
                                    </div>
                                </div>
                            </label>

                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-download me-2"></i> Generate & Unduh Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling simpel untuk efek Radio Button menjadi Card */
    .cursor-pointer { cursor: pointer; }
    input:checked + .card { border-color: #198754 !important; background-color: #f8fff9; }
    input:checked + .card .check-icon { display: block !important; }
</style>
<?= $this->endSection() ?>