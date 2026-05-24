<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4 class="mb-3"><i class="bi bi-clipboard-data"></i> Kesiapan Akreditasi</h4>

<?php if (empty($instrumen)): ?>
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Prodi belum memiliki instrumen LAM aktif, atau prodi aktif belum dipilih. Pilih prodi di navbar dan tetapkan instrumen di menu Instrumen LAM.</div>
<?php else: ?>

<?php
$pct = $total['max_nilai'] > 0 ? $total['pct'] : 0;
$pctColor = $pct >= 85 ? 'success' : ($pct >= 70 ? 'primary' : ($pct >= 55 ? 'warning' : 'danger'));
$lkpsPct = $lkpsTotal ? round($lkpsTerisi / $lkpsTotal * 100) : 0;
$ledPct  = $ledTotal ? round($ledTerisi / $ledTotal * 100) : 0;
$butirPct = $butirTotal ? round($butirDinilai / $butirTotal * 100) : 0;
?>

<!-- Ringkasan atas -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center h-100 border-<?= $pctColor ?>">
            <div class="card-body">
                <div class="display-6 fw-bold text-<?= $pctColor ?>"><?= number_format($total['nilai_akhir'], 1) ?></div>
                <div class="text-muted small">Nilai Akhir / <?= (int) $total['max_nilai'] ?></div>
                <span class="badge bg-<?= $pctColor ?> mt-1"><?= esc($total['predikat']) ?> (<?= $pct ?>%)</span>
            </div>
        </div>
    </div>
    <?php
    $kpis = [
        ['Butir Dinilai', $butirDinilai, $butirTotal, $butirPct],
        ['Tabel LKPS Terisi', $lkpsTerisi, $lkpsTotal, $lkpsPct],
        ['Narasi LED Terisi', $ledTerisi, $ledTotal, $ledPct],
    ];
    foreach ($kpis as [$lbl, $cur, $tot, $p]): $col = $p >= 80 ? 'success' : ($p >= 50 ? 'warning' : 'danger'); ?>
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between"><span class="fw-semibold"><?= $cur ?>/<?= $tot ?></span><span class="text-<?= $col ?>"><?= $p ?>%</span></div>
                <div class="text-muted small mb-2"><?= $lbl ?></div>
                <div class="progress" style="height:6px"><div class="progress-bar bg-<?= $col ?>" style="width:<?= $p ?>%"></div></div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>

<div class="row g-4">
    <!-- Skor per kriteria -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white fw-semibold">Skor per Kriteria — <?= esc($instrumen['nama']) ?></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light"><tr><th>Kriteria</th><th class="text-center">Dinilai</th><th class="text-center">Skor</th><th class="text-end">Bobot</th></tr></thead>
                    <tbody>
                    <?php foreach ($kriteria as $k): ?>
                        <tr>
                            <td><strong><?= esc($k['kode']) ?></strong> <?= esc($k['nama']) ?></td>
                            <td class="text-center small <?= $k['jml_isi'] < $k['jml_butir'] ? 'text-warning' : 'text-success' ?>"><?= $k['jml_isi'] ?>/<?= $k['jml_butir'] ?></td>
                            <td class="text-center"><?= $k['skor_rata'] !== null ? '<span class="badge bg-info text-dark">'.number_format($k['skor_rata'],2).'</span>' : '<span class="text-muted">-</span>' ?></td>
                            <td class="text-end"><?= esc($k['total_bobot']) ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Lengkapi butir lewat menu <a href="<?= site_url('penilaian') ?>">Penilaian LAM</a> (atau Jalankan Auto-Skor).</div>
        </div>
    </div>

    <!-- Kelengkapan data -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-white fw-semibold">Kelengkapan Data Pendukung</div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <tbody>
                    <?php foreach ($data as [$label, $jml, $min]): $ok = $jml >= $min; ?>
                        <tr>
                            <td style="width:34px" class="text-center">
                                <i class="bi <?= $ok ? 'bi-check-circle-fill text-success' : 'bi-exclamation-circle text-danger' ?>"></i>
                            </td>
                            <td><?= esc($label) ?></td>
                            <td class="text-end"><?= $ok ? '<span class="badge bg-light text-dark border">'.$jml.'</span>' : '<span class="badge bg-danger">belum ada</span>' ?></td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">Centang hijau = data sudah ada. DTPR disarankan ≥ 5 dosen.</div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-robot"></i> Ringkasan Eksekutif (AI)</span>
        <button type="button" id="btnAiRingkasan" class="btn btn-sm btn-outline-info"><i class="bi bi-magic"></i> Buat dengan AI</button>
    </div>
    <div class="card-body">
        <textarea id="ringkasanOut" class="form-control" rows="8" placeholder="Klik 'Buat dengan AI' untuk menghasilkan draf ringkasan eksekutif dari data prodi (skor, dosen, mahasiswa, tracer, dll). Hasilnya dapat Anda salin ke LED."></textarea>
        <small class="text-muted">Draf otomatis — harap ditinjau & disesuaikan sebelum dipakai.</small>
    </div>
</div>

<div class="mt-4 d-flex gap-2 flex-wrap">
    <a href="<?= site_url('lkps') ?>" class="btn btn-outline-success"><i class="bi bi-file-earmark-spreadsheet"></i> Generate LKPS</a>
    <a href="<?= site_url('led') ?>" class="btn btn-outline-primary"><i class="bi bi-file-earmark-word"></i> Generate LED</a>
    <a href="<?= site_url('penilaian') ?>" class="btn btn-outline-secondary"><i class="bi bi-clipboard-check"></i> Penilaian LAM</a>
</div>

<?php endif ?>

<script>
document.getElementById('btnAiRingkasan')?.addEventListener('click', function () {
    var b = this, orig = b.innerHTML, out = document.getElementById('ringkasanOut');
    b.disabled = true; b.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Membuat...';
    fetch('<?= site_url('ai/draft/ringkasan') ?>', {method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(function (r) { return r.json(); }).then(function (d) {
            if (d && d.ok) { out.value = d.text; } else { alert(d && d.error ? d.error : 'Gagal membuat ringkasan.'); }
        }).catch(function () { alert('Gagal menghubungi server.'); })
          .finally(function () { b.disabled = false; b.innerHTML = orig; });
});
</script>

<?= $this->endSection() ?>
