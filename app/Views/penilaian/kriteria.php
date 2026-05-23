<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$canInput = auth()->user()->can('penilaian.input');
$ipoLabels  = ['input'=>'Input (I)','proses'=>'Proses (P)','output'=>'Output (O)'];
$ipoColors  = ['input'=>'primary','proses'=>'warning','output'=>'success'];
$skorLabels = [4=>'Sangat Baik',3=>'Baik',2=>'Cukup',1=>'Kurang'];
$skorColors = [4=>'success',3=>'primary',2=>'warning',1=>'danger'];
$taParam    = $taId ? '?ta=' . $taId : '';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0">
            <span class="badge bg-dark me-2"><?= esc($k['kode']) ?></span>
            <?= esc($k['nama']) ?>
        </h4>
        <small class="text-muted">Bobot: <?= $k['bobot'] ?> poin &middot; <?= count($butir) ?> butir penilaian</small>
    </div>
    <a href="<?= site_url('penilaian') . $taParam ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<!-- Ringkasan skor kriteria ini -->
<?php
$jmlIsi  = count(array_filter($butir, fn($b) => ($b['skor_final'] ?? $b['skor_auto']) !== null));
$avgSum  = 0; $avgWeight = 0;
foreach ($butir as $b) {
    $s = $b['skor_final'] ?? $b['skor_auto'] ?? null;
    if ($s !== null) { $avgSum += (float)$s * (float)$b['bobot']; $avgWeight += (float)$b['bobot']; }
}
$avgSkor = $avgWeight > 0 ? round($avgSum / $avgWeight, 2) : null;
$pctBar  = $avgSkor !== null ? round($avgSkor / 4 * 100) : 0;
$barColor = $pctBar >= 75 ? 'success' : ($pctBar >= 50 ? 'primary' : ($pctBar >= 25 ? 'warning' : 'secondary'));
?>
<div class="card mb-3">
    <div class="card-body py-2">
        <div class="row align-items-center">
            <div class="col-md-2 text-center">
                <div class="fs-2 fw-bold text-<?= $barColor ?>"><?= $avgSkor !== null ? $avgSkor : '—' ?></div>
                <small class="text-muted">Skor Rata-rata / 4.00</small>
            </div>
            <div class="col-md-7">
                <div class="progress" style="height:16px">
                    <div class="progress-bar bg-<?= $barColor ?> progress-bar-striped" style="width:<?= max(2, $pctBar) ?>%">
                        <?= $avgSkor !== null ? $pctBar . '%' : '' ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <span class="text-muted small">Butir terisi: <strong><?= $jmlIsi ?>/<?= count($butir) ?></strong></span>
            </div>
        </div>
    </div>
</div>

<!-- Butir penilaian -->
<?php foreach ($butir as $idx => $b): ?>
<?php
$skorFinal = $b['skor_final'] ?? $b['skor_auto'] ?? null;
$hasManual = $b['skor_manual'] !== null;
$hasAuto   = $b['skor_auto'] !== null;
$cardBorder = $skorFinal !== null
    ? ($skorFinal >= 3.5 ? 'border-success' : ($skorFinal >= 2.5 ? 'border-primary' : ($skorFinal >= 1.5 ? 'border-warning' : 'border-danger')))
    : 'border-light';
?>
<div class="card mb-3 <?= $cardBorder ?>" id="butir-<?= $b['id'] ?>">
    <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <div>
            <span class="badge bg-<?= $ipoColors[$b['jenis_ipo']] ?? 'secondary' ?> me-1"><?= $ipoLabels[$b['jenis_ipo']] ?? $b['jenis_ipo'] ?></span>
            <strong><?= esc($b['kode']) ?></strong>
            <span class="ms-2"><?= esc($b['judul']) ?></span>
            <span class="badge bg-light text-dark ms-1"><?= $b['bobot'] ?> pts</span>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <?php if ($hasAuto): ?>
            <span class="badge bg-info text-dark" title="Skor auto dari database">
                <i class="bi bi-cpu"></i> Auto: <?= number_format($b['skor_auto'], 2) ?>
            </span>
            <?php endif ?>
            <?php if ($hasManual): ?>
            <span class="badge bg-<?= $skorColors[(int)$b['skor_manual']] ?? 'secondary' ?>">
                <i class="bi bi-hand-index"></i> Manual: <?= number_format($b['skor_manual'], 0) ?>
            </span>
            <?php endif ?>
            <?php if ($skorFinal !== null): ?>
            <span class="badge bg-<?= $skorColors[(int)round($skorFinal)] ?? 'secondary' ?> fs-6">
                Final: <?= number_format($skorFinal, 2) ?>
            </span>
            <?php else: ?>
            <span class="badge bg-secondary">Belum dinilai</span>
            <?php endif ?>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <!-- Rubrik -->
            <div class="col-md-8">
                <?php if ($hasAuto): ?>
                <div class="alert alert-info py-1 px-2 small mb-2">
                    <i class="bi bi-cpu"></i> <strong>Dasar auto-skor:</strong>
                    <?= esc($autoBasis[$b['kode']] ?? 'Dihitung otomatis dari data prodi.') ?>
                    <span class="text-muted d-block mt-1"><i class="bi bi-exclamation-triangle"></i> Estimasi awal — harap diverifikasi sesuai bukti & rubrik resmi, lalu isi skor manual bila perlu.</span>
                </div>
                <?php endif ?>
                <?php if (!empty($b['deskripsi'])): ?>
                <p class="text-muted small mb-2"><i class="bi bi-info-circle"></i> <?= esc($b['deskripsi']) ?></p>
                <?php endif ?>
                <?php if (!empty($b['rubrik'])): ?>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.82rem">
                        <thead class="table-light">
                            <tr><th width="60">Skor</th><th width="110">Label</th><th>Deskriptor</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($b['rubrik'] as $r): ?>
                        <tr class="<?= ($skorFinal !== null && (int)round($skorFinal) == $r['skor']) ? 'table-success fw-semibold' : '' ?>">
                            <td class="text-center fw-bold text-<?= $skorColors[$r['skor']] ?? 'secondary' ?>"><?= $r['skor'] ?></td>
                            <td><?= esc($r['label']) ?></td>
                            <td><?= esc($r['deskriptor']) ?></td>
                        </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <?php endif ?>
            </div>

            <!-- Form input skor manual -->
            <?php if ($canInput): ?>
            <div class="col-md-4">
                <form method="post" action="<?= site_url("penilaian/skor/{$b['id']}/save") ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <?php if ($taId): ?><input type="hidden" name="tahun_akademik_id" value="<?= $taId ?>"><?php endif ?>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Skor Manual (1-4)</label>
                        <select name="skor_manual" class="form-select form-select-sm">
                            <option value="">— Hapus manual / pakai auto —</option>
                            <?php foreach ([4,3,2,1] as $s): ?>
                            <option value="<?= $s ?>" <?= (string)$b['skor_manual'] === (string)$s ? 'selected' : '' ?>>
                                <?= $s ?> — <?= $skorLabels[$s] ?>
                            </option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-muted">Kosong = pakai skor auto <?= $hasAuto ? '(' . number_format($b['skor_auto'], 2) . ')' : '' ?></small>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label fw-semibold small mb-0">Catatan</label>
                            <button type="button" class="btn btn-sm btn-outline-info py-0 px-1 btn-ai-catatan"
                                data-butir="<?= $b['id'] ?>" data-skor="<?= esc($b['skor_manual'] ?? $b['skor_auto'] ?? '', 'attr') ?>"
                                title="Buat draf catatan dengan AI"><i class="bi bi-robot"></i> Bantu AI</button>
                        </div>
                        <textarea name="catatan" class="form-control form-control-sm mt-1" rows="3"
                            placeholder="Narasi singkat dasar penilaian."><?= esc($b['catatan'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Bukti (PDF)</label>
                        <?php $bk = $b['link_bukti'] ?? ''; $isUrl = str_starts_with((string)$bk, 'http'); ?>
                        <?php if (!empty($bk)): ?>
                        <div class="mb-1 small">
                            <?php if ($isUrl): ?>
                                <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Bukti (link lama)</a>
                            <?php else: ?>
                                <a href="<?= site_url('penilaian/bukti/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti PDF</a>
                            <?php endif ?>
                            <label class="ms-2 text-danger"><input type="checkbox" name="hapus_bukti" value="1"> hapus</label>
                        </div>
                        <?php endif ?>
                        <input type="file" name="bukti_pdf" class="form-control form-control-sm" accept="application/pdf">
                        <small class="text-muted">Unggah PDF (otomatis dikompres bila Ghostscript terpasang). Maks <?= (int) env('storage.maxUploadMb', 20) ?> MB.</small>
                    </div>

                    <?php if ($b['skor_updated_at']): ?>
                    <div class="text-muted" style="font-size:.75rem">
                        <i class="bi bi-clock"></i> Diperbarui: <?= esc($b['skor_updated_at']) ?>
                    </div>
                    <?php endif ?>

                    <button type="submit" class="btn btn-primary btn-sm mt-2 w-100">
                        <i class="bi bi-save"></i> Simpan Skor
                    </button>
                </form>
            </div>
            <?php else: ?>
            <div class="col-md-4">
                <?php if ($b['catatan']): ?>
                <div class="alert alert-light border p-2">
                    <small class="fw-semibold">Catatan:</small><br>
                    <small><?= nl2br(esc($b['catatan'])) ?></small>
                </div>
                <?php endif ?>
                <?php if (!empty($b['link_bukti'])): $bk = $b['link_bukti']; ?>
                <div class="mt-1">
                    <?php if (str_starts_with((string)$bk, 'http')): ?>
                        <a href="<?= esc($bk, 'attr') ?>" target="_blank" rel="noopener"><i class="bi bi-box-arrow-up-right"></i> Buka bukti</a>
                    <?php else: ?>
                        <a href="<?= site_url('penilaian/bukti/' . $bk) ?>" target="_blank"><i class="bi bi-file-earmark-pdf text-danger"></i> Lihat bukti PDF</a>
                    <?php endif ?>
                </div>
                <?php endif ?>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>
<?php endforeach ?>

<?php if (empty($butir)): ?>
<div class="alert alert-info"><i class="bi bi-info-circle"></i> Belum ada butir penilaian untuk kriteria ini. Jalankan seeder LamInfokomD3Seeder terlebih dahulu.</div>
<?php endif ?>

<!-- Navigasi antar kriteria -->
<?php if ($instrumen && $prodiId): ?>
<?php
$db = \Config\Database::connect();
$allK = $db->query("SELECT id, kode, nama FROM lam_kriteria WHERE lam_instrumen_id=? ORDER BY urutan", [$instrumen['id']])->getResultArray();
$curIdx = array_search($kriteriaId, array_column($allK, 'id'));
$prev = $curIdx > 0 ? $allK[$curIdx - 1] : null;
$next = isset($allK[$curIdx + 1]) ? $allK[$curIdx + 1] : null;
?>
<div class="d-flex justify-content-between mt-4">
    <?php if ($prev): ?>
    <a href="<?= site_url('penilaian/kriteria/' . $prev['id']) . $taParam ?>" class="btn btn-outline-secondary">
        <i class="bi bi-chevron-left"></i> <?= esc($prev['kode']) ?> — <?= esc($prev['nama']) ?>
    </a>
    <?php else: ?>
    <span></span>
    <?php endif ?>
    <?php if ($next): ?>
    <a href="<?= site_url('penilaian/kriteria/' . $next['id']) . $taParam ?>" class="btn btn-outline-primary">
        <?= esc($next['kode']) ?> — <?= esc($next['nama']) ?> <i class="bi bi-chevron-right"></i>
    </a>
    <?php endif ?>
</div>
<?php endif ?>

<script>
document.querySelectorAll('.btn-ai-catatan').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var form = btn.closest('form');
        var ta   = form ? form.querySelector('textarea[name="catatan"]') : null;
        var sel  = form ? form.querySelector('select[name="skor_manual"]') : null;
        var skor = (sel && sel.value) ? sel.value : (btn.dataset.skor || '');
        var orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Membuat...';
        var body = new URLSearchParams(); body.append('skor', skor);
        fetch('<?= site_url('penilaian/catatan-ai/') ?>' + btn.dataset.butir, {
            method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: body
        }).then(function (r) { return r.json(); }).then(function (d) {
            if (d && d.ok && ta) {
                ta.value = d.text;
            } else {
                alert(d && d.error ? d.error : 'Gagal membuat draf.');
            }
        }).catch(function () { alert('Gagal menghubungi server.'); })
          .finally(function () { btn.disabled = false; btn.innerHTML = orig; });
    });
});
</script>

<?= $this->endSection() ?>
