<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Eviden Visitasi &mdash; <?= esc($prodi['prodi_nama'] ?? 'Prodi') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<style>
    @media print { .no-print { display: none !important; } body { font-size: 11px; } }
    .ppepp-cell { vertical-align: top; font-size: 12px; line-height: 1.4; padding: 8px; }
    .ppepp-cell .narasi { white-space: pre-wrap; }
    .ppepp-cell .bukti { margin-top: 6px; padding-top: 6px; border-top: 1px dashed #ccc; }
    .ppepp-cell .bukti a { font-size: 11px; word-break: break-all; }
    th.fase { font-size: 11px; text-align: center; }
    .kriteria-bar { background: #e9ecef; padding: 8px 12px; font-weight: 600; margin-top: 16px; border-left: 4px solid #0d6efd; }
</style>
</head>
<body class="bg-light">
<div class="container-fluid py-4" style="max-width:1400px">

    <div class="text-center mb-3">
        <h4 class="mb-1"><i class="bi bi-shield-check"></i> Eviden PPEPP &mdash; Visitasi Akreditasi</h4>
        <div class="text-muted small">
            <strong><?= esc($prodi['prodi_nama'] ?? '-') ?> <?= esc($prodi['jenjang'] ?? '') ?></strong>
            <?php if (!empty($prodi['upps_nama'])): ?> &middot; <?= esc(ucfirst($prodi['upps_jenis'] ?? '')) ?> <?= esc($prodi['upps_nama']) ?><?php endif ?>
            <?php if (!empty($prodi['pt_nama'])): ?> &middot; <?= esc($prodi['pt_nama']) ?><?php endif ?>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-2 no-print">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak / Simpan PDF</button>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="alert alert-warning text-center">Tidak ada data eviden tersedia untuk prodi ini.</div>
    <?php else: foreach ($grouped as $iid => $instr): ?>
        <div class="bg-primary text-white px-3 py-2 fw-semibold mb-0"><i class="bi bi-bookmark-star"></i> <?= esc($instr['nama']) ?></div>
        <?php foreach ($instr['kriteria'] as $kid => $kr): ?>
            <div class="kriteria-bar"><span class="badge bg-primary"><?= esc($kr['kode']) ?></span> <?= esc($kr['nama']) ?></div>
            <?php foreach ($kr['butir'] as $b):
                $ev = $evidenByButir[(int)$b['id']] ?? null;
                $bk = $ev ? ($buktiByEviden[(int)$ev['id']] ?? []) : [];
            ?>
            <div class="card mb-2">
                <div class="card-header bg-white py-2">
                    <small class="text-muted"><?= esc($b['butir_kode']) ?></small> &middot; <strong><?= esc($b['judul']) ?></strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="table-light">
                                <th class="fase" style="width:20%">Penetapan</th>
                                <th class="fase" style="width:20%">Pelaksanaan</th>
                                <th class="fase" style="width:20%">Evaluasi</th>
                                <th class="fase" style="width:20%">Pengendalian</th>
                                <th class="fase" style="width:20%">Peningkatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach (['penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan'] as $f): ?>
                                <td class="ppepp-cell">
                                    <?php if (!empty($ev[$f])): ?>
                                        <div class="narasi"><?= esc($ev[$f]) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">&mdash;</span>
                                    <?php endif ?>
                                    <?php if (!empty($bk[$f])): ?>
                                    <div class="bukti">
                                        <div class="fw-semibold small"><i class="bi bi-paperclip"></i> Bukti:</div>
                                        <?php foreach ($bk[$f] as $bki): ?>
                                            <div>&bull; <a href="<?= esc($bki['url'], 'attr') ?>" target="_blank"><?= esc($bki['label']) ?></a></div>
                                        <?php endforeach ?>
                                    </div>
                                    <?php endif ?>
                                </td>
                                <?php endforeach ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endforeach ?>
        <?php endforeach ?>
    <?php endforeach; endif ?>

    <p class="text-center text-muted small mt-4 mb-0">Dicetak: <?= esc(date('d-m-Y H:i')) ?> &middot; Sistem Eviden PPEPP</p>
</div>
</body>
</html>
