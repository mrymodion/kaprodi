<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$KAT = \App\Models\KondisiEksternalModel::KATEGORI;
$DMP = \App\Models\KondisiEksternalModel::DAMPAK_LABELS;
$totalEntries = array_sum(array_map('count', $grouped));
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-file-text"></i> Narasi LED &mdash; <span class="badge bg-primary">A</span> Kondisi Eksternal</h4>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
        <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Input</a>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show small py-2 no-print"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small no-print">
    <i class="bi bi-robot"></i> <strong>Sprint 3 aktif:</strong> tombol <em>Draft via AI</em> per sub-bagian akan men-sintesis data terstruktur Anda menjadi paragraf akademik LED. Edit hasilnya bila perlu, lalu simpan. Hasil simpan dipakai untuk cetak & dokumen LED final.
    <?php if (!$aiReady): ?>
        <br><span class="text-danger"><i class="bi bi-x-circle"></i> AI belum siap: <code>GROQ_API_KEY</code> belum di-set di <code>.env</code>. Tombol AI dinonaktifkan.</span>
    <?php endif ?>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    .ai-editor { display: none !important; }
    .narasi-final { font-size: 12pt; line-height: 1.6; }
}
.sub-section { border: 1px solid #dee2e6; border-radius: .5rem; margin-bottom: 1.5rem; }
.sub-section-header { padding: .75rem 1rem; background: #e9ecef; border-radius: .5rem .5rem 0 0; font-weight: 600; }
.sub-section-body { padding: 1rem; }
.narasi-final { white-space: pre-wrap; line-height: 1.6; }
.narasi-final.kosong { color: #888; font-style: italic; }
.ai-meta { font-size: .8em; color: #666; }
</style>

<div class="card">
    <div class="card-body">
        <h4 class="mb-3 text-center">A. Kondisi Eksternal</h4>

        <?php if ($totalEntries === 0): ?>
            <div class="alert alert-warning text-center">Belum ada entri Kondisi Eksternal. <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>">Mulai input</a>.</div>
        <?php else: ?>

        <p>Analisis kondisi eksternal Program Studi disusun berdasarkan kerangka PESTEL, kondisi pasar, serta identifikasi peluang dan ancaman. Total <strong><?= $totalEntries ?></strong> faktor eksternal teridentifikasi yang disintesis menjadi 4 sub-bagian berikut.</p>

        <?php foreach ($subBagian as $sub => $info):
            $saved = $narasiSaved[$sub] ?? null;
            // hitung entries terkait untuk indikasi
            $relatedCount = 0;
            foreach ($info['kategori'] as $k) $relatedCount += count($grouped[$k] ?? []);
            $kategoriLabel = implode(', ', array_map(fn($k) => $KAT[$k]['label'] ?? $k, $info['kategori']));
        ?>
        <div class="sub-section" id="sub-<?= esc($sub) ?>">
            <div class="sub-section-header d-flex justify-content-between align-items-center">
                <span><span class="badge bg-primary"><?= esc($sub) ?></span> <?= esc($info['judul']) ?></span>
                <div class="no-print d-flex gap-2 align-items-center">
                    <?php if ($sub !== 'A.4'): ?>
                        <small class="text-muted"><?= $relatedCount ?> entri terkait</small>
                    <?php else: ?>
                        <small class="text-muted">sintesis A.1-A.3</small>
                    <?php endif ?>
                    <?php if ($aiReady): ?>
                    <button type="button" class="btn btn-sm btn-primary" onclick="draftAi('<?= esc($sub) ?>', this)" id="btn-draft-<?= esc($sub) ?>">
                        <i class="bi bi-robot"></i> <?= $saved ? 'Regenerate' : 'Draft via AI' ?>
                    </button>
                    <?php endif ?>
                </div>
            </div>
            <div class="sub-section-body">
                <?php if (!empty($info['kategori'])): ?>
                <div class="small text-muted mb-2 no-print"><i class="bi bi-tag"></i> Mencakup kategori: <?= esc($kategoriLabel) ?></div>
                <?php endif ?>

                <?php if ($saved): ?>
                    <div class="narasi-final" id="narasi-<?= esc($sub) ?>"><?= esc($saved['narasi']) ?></div>
                    <?php if (!empty($saved['generated_at'])): ?>
                    <div class="ai-meta mt-2 no-print">
                        <i class="bi bi-clock-history"></i> Generated: <?= esc($saved['generated_at']) ?>
                        <?php if (!empty($saved['updated_at']) && $saved['updated_at'] !== $saved['generated_at']): ?>
                            &middot; Edited: <?= esc($saved['updated_at']) ?>
                        <?php endif ?>
                        <?php if ((int) $saved['source_count'] !== $relatedCount && $sub !== 'A.4'): ?>
                            &middot; <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Data sudah berubah (<?= $saved['source_count'] ?> → <?= $relatedCount ?>) &mdash; pertimbangkan regenerate.</span>
                        <?php endif ?>
                    </div>
                    <?php endif ?>
                <?php else: ?>
                    <div class="narasi-final kosong" id="narasi-<?= esc($sub) ?>">Belum ada narasi tersimpan. Klik <strong>Draft via AI</strong> untuk membuat draft dari data terstruktur Anda<?= $sub === 'A.4' ? ' (perlu A.1-A.3 sudah ter-draft dahulu)' : '' ?>.</div>
                <?php endif ?>

                <div class="ai-editor mt-3 no-print" id="editor-<?= esc($sub) ?>" style="display:none">
                    <form method="post" action="<?= site_url("akreditasi/kondisi-eksternal/narasi/{$sub}/save") ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="generated" value="1">
                        <input type="hidden" name="source_count" id="sc-<?= esc($sub) ?>" value="<?= $relatedCount ?>">
                        <label class="form-label small fw-semibold mb-1">Edit & simpan narasi:</label>
                        <textarea name="narasi" class="form-control form-control-sm" rows="8" id="ta-<?= esc($sub) ?>"></textarea>
                        <div class="mt-2 d-flex gap-2">
                            <button class="btn btn-sm btn-success"><i class="bi bi-check"></i> Simpan</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cancelEdit('<?= esc($sub) ?>')">Batal</button>
                        </div>
                    </form>
                </div>

                <?php if ($saved): ?>
                <div class="mt-2 no-print">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="editExisting('<?= esc($sub) ?>')"><i class="bi bi-pencil"></i> Edit Manual</button>
                </div>
                <?php endif ?>
            </div>
        </div>
        <?php endforeach ?>
        <?php endif ?>
    </div>
</div>

<script>
const CSRF = { name: '<?= csrf_token() ?>', hash: '<?= csrf_hash() ?>' };

async function draftAi(sub, btn) {
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> AI sedang menulis...';
    try {
        const fd = new FormData();
        fd.append(CSRF.name, CSRF.hash);
        const res = await fetch(`<?= site_url('akreditasi/kondisi-eksternal/narasi') ?>/${sub}/draft`, {
            method: 'POST', body: fd,
        });
        const data = await res.json();
        if (!data.ok) { alert(data.error || 'Gagal generate AI.'); return; }
        // Tampilkan di editor untuk review/edit sebelum simpan
        document.getElementById('ta-' + sub).value = data.narasi;
        document.getElementById('sc-' + sub).value = data.source_count;
        document.getElementById('editor-' + sub).style.display = 'block';
        document.getElementById('narasi-' + sub).style.opacity = '0.5';
        btn.innerHTML = '<i class="bi bi-robot"></i> Regenerate';
    } catch (e) {
        alert('Error: ' + e.message);
    } finally {
        btn.disabled = false;
        if (btn.innerHTML.includes('sedang')) btn.innerHTML = orig;
    }
}

function editExisting(sub) {
    const narasi = document.getElementById('narasi-' + sub).innerText;
    document.getElementById('ta-' + sub).value = narasi;
    document.querySelector('#editor-' + sub + ' input[name="generated"]').value = '0';
    document.getElementById('editor-' + sub).style.display = 'block';
}

function cancelEdit(sub) {
    document.getElementById('editor-' + sub).style.display = 'none';
    document.getElementById('narasi-' + sub).style.opacity = '1';
}
</script>

<?= $this->endSection() ?>
