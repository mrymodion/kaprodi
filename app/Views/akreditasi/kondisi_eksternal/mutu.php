<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$warnaSkor = fn($s) => $s >= 4 ? 'success' : ($s >= 3 ? 'info' : ($s >= 2 ? 'warning' : 'danger'));
$aiSet = array_flip($aiDimensi);
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-speedometer2"></i> Evaluasi Mutu &mdash; <span class="badge bg-primary">A</span> Kondisi Eksternal</h4>
    <div class="d-flex gap-2">
        <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Input</a>
        <a href="<?= site_url('akreditasi/kondisi-eksternal/narasi') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-text"></i> Narasi LED</a>
    </div>
</div>

<div class="alert alert-light border small">
    <i class="bi bi-info-circle"></i> <strong>Scorecard internal Sprint 4.</strong> Skor 1-4 dihitung dari 6 dimensi mutu (heuristik). Tombol <em>AI Judge</em> menilai ulang 3 dimensi kualitatif (kedalaman, respons prodi, sintesis narasi) secara lebih cermat. Ini panduan persiapan, bukan penilaian resmi asesor.
</div>

<?php if ($hasil['n_entries'] === 0): ?>
    <div class="alert alert-warning text-center"><i class="bi bi-inbox"></i> Belum ada entri Kondisi Eksternal. <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>">Mulai input</a> atau gunakan Saran AI.</div>
<?php else: ?>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card h-100 border-<?= $hasil['label']['warna'] ?>">
            <div class="card-body text-center d-flex flex-column justify-content-center">
                <div class="text-muted small">Skor Total Mutu</div>
                <div class="display-3 fw-bold text-<?= $hasil['label']['warna'] ?>" id="skorTotal"><?= number_format($hasil['skor_total'], 2) ?></div>
                <div class="mb-2"><span class="badge bg-<?= $hasil['label']['warna'] ?> fs-6" id="labelTotal"><?= esc($hasil['label']['label']) ?></span></div>
                <div class="small text-muted">dari skala 4.00 &middot; <?= $hasil['n_entries'] ?> entri</div>
                <div class="mt-2 small" id="aiModeIndicator">
                    <span class="badge bg-secondary"><i class="bi bi-calculator"></i> Mode Heuristik</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart"></i> Skor per Dimensi</span>
                <?php if ($aiReady): ?>
                <button class="btn btn-sm btn-primary" id="btnAiJudge" onclick="runAiJudge(this)"><i class="bi bi-robot"></i> Nilai dengan AI Judge</button>
                <?php else: ?>
                <span class="badge bg-secondary" title="GROQ_API_KEY belum di-set"><i class="bi bi-robot"></i> AI Judge nonaktif</span>
                <?php endif ?>
            </div>
            <div class="card-body py-2" id="dimensiList">
                <?php foreach ($hasil['final'] as $dim => $skor): [$label, $ikon] = $meta[$dim]; ?>
                <div class="mb-2" data-dim="<?= $dim ?>">
                    <div class="d-flex justify-content-between align-items-center small">
                        <span>
                            <i class="bi <?= $ikon ?>"></i> <?= esc($label) ?>
                            <span class="text-muted">(bobot <?= $bobot[$dim] ?>)</span>
                            <?php if (isset($aiSet[$dim])): ?><span class="badge bg-light text-primary border" title="Dimensi yang dinilai AI Judge"><i class="bi bi-robot"></i> AI</span><?php endif ?>
                        </span>
                        <span class="fw-bold text-<?= $warnaSkor($skor) ?>" data-skor><?= $skor ?>/4</span>
                    </div>
                    <div class="progress" style="height:7px">
                        <div class="progress-bar bg-<?= $warnaSkor($skor) ?>" data-bar style="width:<?= ($skor/4)*100 ?>%"></div>
                    </div>
                    <?php if (isset($hasil['ai'][$dim]['justifikasi'])): ?>
                    <div class="small text-muted fst-italic mt-1" data-justif><i class="bi bi-quote"></i> <?= esc($hasil['ai'][$dim]['justifikasi']) ?></div>
                    <?php else: ?>
                    <div class="small text-muted fst-italic mt-1 d-none" data-justif></div>
                    <?php endif ?>
                </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-list-check"></i> Rekomendasi Perbaikan (prioritas)</div>
    <div class="card-body" id="rekomendasiList">
        <?php if (empty($hasil['rekomendasi'])): ?>
            <div class="alert alert-success small mb-0"><i class="bi bi-check-circle"></i> Semua dimensi sudah optimal (skor 4). Pertahankan dan perbarui berkala.</div>
        <?php else: foreach ($hasil['rekomendasi'] as $r): ?>
            <div class="d-flex gap-2 align-items-start mb-2">
                <span class="badge bg-<?= $warnaSkor($r['skor']) ?>"><?= $r['skor'] ?>/4</span>
                <div class="small"><strong><?= esc($r['label']) ?>.</strong> <?= esc($r['saran']) ?></div>
            </div>
        <?php endforeach; endif ?>
    </div>
</div>

<?php endif ?>

<script>
const MUTU = {
    meta: <?= json_encode($meta) ?>,
    bobot: <?= json_encode($bobot) ?>,
    csrfName: '<?= csrf_token() ?>',
    csrfHash: '<?= csrf_hash() ?>',
};
function warnaSkor(s){ return s>=4?'success':(s>=3?'info':(s>=2?'warning':'danger')); }

async function runAiJudge(btn){
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> AI menilai...';
    try {
        const fd = new FormData();
        fd.append(MUTU.csrfName, MUTU.csrfHash);
        const res = await fetch('<?= site_url('akreditasi/kondisi-eksternal/mutu/ai') ?>', { method:'POST', body: fd });
        const data = await res.json();
        if (!data.ok){ alert(data.error || 'AI Judge gagal.'); return; }
        applyHasil(data.hasil);
        document.getElementById('aiModeIndicator').innerHTML =
            '<span class="badge bg-primary"><i class="bi bi-robot"></i> Mode Heuristik + AI Judge</span>';
        btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Nilai ulang AI Judge';
    } catch(e){
        alert('Error: ' + e.message);
        btn.innerHTML = orig;
    } finally {
        btn.disabled = false;
    }
}

function applyHasil(h){
    // total
    document.getElementById('skorTotal').textContent = h.skor_total.toFixed(2);
    const lt = document.getElementById('labelTotal');
    lt.textContent = h.label.label;
    lt.className = 'badge bg-' + h.label.warna + ' fs-6';
    // dimensi
    document.querySelectorAll('#dimensiList [data-dim]').forEach(row => {
        const dim = row.getAttribute('data-dim');
        const skor = h.final[dim];
        const w = warnaSkor(skor);
        const sEl = row.querySelector('[data-skor]');
        sEl.textContent = skor + '/4';
        sEl.className = 'fw-bold text-' + w;
        const bar = row.querySelector('[data-bar]');
        bar.className = 'progress-bar bg-' + w;
        bar.style.width = (skor/4*100) + '%';
        const jEl = row.querySelector('[data-justif]');
        if (h.ai[dim] && h.ai[dim].justifikasi){
            jEl.innerHTML = '<i class="bi bi-quote"></i> ' + h.ai[dim].justifikasi;
            jEl.classList.remove('d-none');
        }
    });
    // rekomendasi
    const wrap = document.getElementById('rekomendasiList');
    if (!h.rekomendasi || h.rekomendasi.length === 0){
        wrap.innerHTML = '<div class="alert alert-success small mb-0"><i class="bi bi-check-circle"></i> Semua dimensi sudah optimal (skor 4).</div>';
    } else {
        wrap.innerHTML = h.rekomendasi.map(r =>
            `<div class="d-flex gap-2 align-items-start mb-2"><span class="badge bg-${warnaSkor(r.skor)}">${r.skor}/4</span><div class="small"><strong>${r.label}.</strong> ${r.saran}</div></div>`
        ).join('');
    }
}
</script>

<?= $this->endSection() ?>
