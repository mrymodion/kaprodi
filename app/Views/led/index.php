<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $canGenerate = auth()->user()->can('led.generate') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="bi bi-file-earmark-word"></i> Generator LED</h4>
        <?php if ($instrumen): ?>
        <small class="text-muted"><?= esc($instrumen['nama']) ?></small>
        <?php endif ?>
    </div>
    <?php if ($canGenerate && $instrumen): ?>
    <form method="post" action="<?= site_url('led/generate') ?>" class="d-flex gap-2 align-items-center">
        <?= csrf_field() ?>
        <select name="tahun_akademik_id" class="form-select form-select-sm" style="width:160px">
            <option value="">— Semua TS —</option>
            <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>"><?= esc($ta['kode']) ?></option>
            <?php endforeach ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-download"></i> Generate LED (.docx)
        </button>
    </form>
    <?php endif ?>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><?= esc(session('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<?php if (!$groqEnabled): ?>
<div class="alert alert-secondary py-2 small"><i class="bi bi-robot"></i> Fitur Groq AI tidak aktif. Set <code>GROQ_API_KEY</code> di file <code>.env</code> untuk mengaktifkan generate narasi otomatis.</div>
<?php endif ?>

<?php if (!$instrumen): ?>
<div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Prodi belum memiliki instrumen LAM yang aktif.</div>
<?= $this->endSection() ?>
<?php return ?>
<?php endif ?>

<div class="row g-3">
    <div class="col-lg-8">

        <?php
        $sectionOrder = ['pendahuluan' => 'Pendahuluan', 'C1' => 'C1', 'C2' => 'C2', 'C3' => 'C3', 'C4' => 'C4', 'C5' => 'C5', 'C6' => 'C6', 'penutup' => 'Penutup'];
        $bagianColors = ['pendahuluan' => 'secondary', 'C1' => 'primary', 'C2' => 'success', 'C3' => 'info', 'C4' => 'warning', 'C5' => 'danger', 'C6' => 'dark', 'penutup' => 'secondary'];
        ?>

        <?php foreach ($sectionOrder as $sectionKey => $sectionLabel): ?>
        <?php if (empty($byBagian[$sectionKey])): continue; endif ?>
        <?php $tabels = $byBagian[$sectionKey] ?>

        <div class="card mb-3">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-<?= $bagianColors[$sectionKey] ?? 'secondary' ?>"><?= esc($sectionLabel) ?></span>
                    <?php if (!in_array($sectionKey, ['pendahuluan','penutup'])): ?>
                    <strong class="ms-1"><?= esc($tabels[0]['kriteria_nama'] ?? '') ?></strong>
                    <?php else: ?>
                    <strong class="ms-1"><?= esc($sectionLabel) ?></strong>
                    <?php endif ?>
                </div>
                <?php
                $filled = count(array_filter($tabels, fn($t) => !empty($t['narasi'])));
                $total  = count($tabels);
                $pct    = $total > 0 ? round($filled / $total * 100) : 0;
                ?>
                <small class="text-muted"><?= $filled ?>/<?= $total ?> terisi</small>
            </div>
            <div class="card-body p-0">
                <div class="accordion accordion-flush" id="accordion-<?= $sectionKey ?>">
                <?php foreach ($tabels as $t): ?>
                    <?php $acId = 'led-' . $t['id'] ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $acId ?>">
                                <span class="me-2 badge bg-light text-dark"><?= esc(ucfirst($t['sub_bagian'] ?? $t['bagian'])) ?></span>
                                <?php if (!empty($t['narasi'])): ?>
                                    <i class="bi bi-check-circle-fill text-success me-1"></i>
                                <?php else: ?>
                                    <i class="bi bi-circle text-muted me-1"></i>
                                <?php endif ?>
                                <?= esc($t['judul']) ?>
                                <?php if ($t['generated_by_ai']): ?>
                                    <span class="badge bg-info ms-2 fw-normal" style="font-size:.65rem"><i class="bi bi-robot"></i> AI</span>
                                <?php endif ?>
                            </button>
                        </h2>
                        <div id="<?= $acId ?>" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <?php if ($canGenerate && $groqEnabled): ?>
                                <div class="d-flex justify-content-end mb-2">
                                    <button class="btn btn-xs btn-outline-info py-0 px-2 btn-groq"
                                            data-id="<?= $t['id'] ?>"
                                            data-target="narasi-<?= $t['id'] ?>">
                                        <i class="bi bi-robot"></i> Generate AI
                                    </button>
                                    <span class="spinner-border spinner-border-sm ms-2 d-none text-info" id="spin-<?= $t['id'] ?>"></span>
                                </div>
                                <?php endif ?>

                                <form method="post" action="<?= site_url('led/narasi/' . $t['id'] . '/save') ?>">
                                    <?= csrf_field() ?>
                                    <textarea name="isi" id="narasi-<?= $t['id'] ?>"
                                        class="form-control form-control-sm mb-2"
                                        rows="8"
                                        placeholder="Tulis narasi di sini, atau gunakan Generate AI…"
                                        <?= $canGenerate ? '' : 'readonly' ?>
                                    ><?= esc($t['narasi'] ?? $t['template_isi'] ?? '') ?></textarea>
                                    <?php if ($canGenerate): ?>
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-floppy"></i> Simpan
                                    </button>
                                    <?php endif ?>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
                </div>
            </div>
        </div>
        <?php endforeach ?>

        <?php if (empty($templates)): ?>
        <div class="alert alert-info">Belum ada template LED. Jalankan seeder <code>LamInfokomD3Seeder</code> terlebih dahulu.</div>
        <?php endif ?>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header py-2"><strong><i class="bi bi-clock-history"></i> File LED Terbaru</strong></div>
            <div class="card-body p-0">
                <?php if (empty($files)): ?>
                <div class="text-muted text-center p-3 small">Belum ada file ter-generate.</div>
                <?php else: ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($files as $f): ?>
                    <li class="list-group-item p-2 small">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-truncate" style="max-width:220px" title="<?= esc($f['name']) ?>">
                                <i class="bi bi-file-earmark-word text-primary"></i>
                                <?= esc($f['name']) ?>
                            </div>
                            <a href="<?= site_url('led/download/' . urlencode($f['name'])) ?>" class="btn btn-xs btn-outline-primary py-0 px-1">
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                        <div class="text-muted" style="font-size:.72rem">
                            <?= number_format($f['size'] / 1024, 1) ?> KB &middot;
                            <?= date('d-m-Y H:i', $f['mtime']) ?>
                        </div>
                    </li>
                    <?php endforeach ?>
                </ul>
                <?php endif ?>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header py-2"><strong><i class="bi bi-info-circle"></i> Panduan</strong></div>
            <div class="card-body small">
                <ul class="mb-0 ps-3">
                    <li>Isi narasi PPEPP tiap kriteria secara manual atau klik <strong>Generate AI</strong> (perlu Groq API key).</li>
                    <li>Narasi AI otomatis diberi tanda <span class="badge bg-info fw-normal" style="font-size:.65rem"><i class="bi bi-robot"></i> AI</span> — wajib ditinjau ulang.</li>
                    <li>Klik <strong>Generate LED (.docx)</strong> untuk ekspor dokumen Word lengkap.</li>
                    <li>Untuk mengaktifkan AI, set <code>GROQ_API_KEY=gsk_xxx</code> di <code>.env</code>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php if ($canGenerate && $groqEnabled): ?>
<script>
document.querySelectorAll('.btn-groq').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id     = btn.dataset.id;
        const target = btn.dataset.target;
        const spin   = document.getElementById('spin-' + id);

        btn.disabled = true;
        if (spin) spin.classList.remove('d-none');

        try {
            const resp = await fetch('<?= site_url('led/groq') ?>/' + id, {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
                body: JSON.stringify({<?= csrf_token() ?>: '<?= csrf_hash() ?>'}),
            });
            const json = await resp.json();
            if (json.success) {
                document.getElementById(target).value = json.narasi;
            } else {
                alert('Error: ' + (json.error || 'Tidak diketahui'));
            }
        } catch (e) {
            alert('Gagal menghubungi server.');
        } finally {
            btn.disabled = false;
            if (spin) spin.classList.add('d-none');
        }
    });
});
</script>
<?php endif ?>

<?= $this->endSection() ?>
