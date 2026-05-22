<?php
$ts  = $tracerSum ?: [];
$rate = (!empty($ts['total']) && $totalLulus > 0) ? round($ts['total'] / $totalLulus * 100) : 0;
$kesLabel = ['sangat_sesuai'=>'Sangat sesuai','sesuai'=>'Sesuai','kurang_sesuai'=>'Kurang sesuai','tidak_sesuai'=>'Tidak sesuai'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { font-family: Helvetica, Arial, sans-serif; }
    body { font-size: 11px; color: #222; }
    h1 { font-size: 16px; color: #0d3b8c; margin: 0 0 2px; }
    h2 { font-size: 13px; color: #0d3b8c; border-bottom: 2px solid #0d3b8c; padding-bottom: 3px; margin: 18px 0 8px; }
    .sub { color: #555; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 6px; }
    th, td { border: 1px solid #bbb; padding: 4px 6px; text-align: left; }
    th { background: #0d3b8c; color: #fff; font-size: 10px; }
    td { font-size: 10px; }
    .kpi { width: 100%; border: none; margin-top: 4px; }
    .kpi td { border: none; padding: 4px; width: 25%; vertical-align: top; }
    .kpi .box { border: 1px solid #ddd; border-radius: 4px; padding: 6px; }
    .kpi .num { font-size: 18px; font-weight: bold; color: #0d3b8c; }
    .kpi .lbl { font-size: 9px; color: #555; }
    .muted { color: #888; }
    .foot { margin-top: 24px; font-size: 9px; color: #888; text-align: center; }
</style>
</head>
<body>
    <h1>Laporan Tracer Study &amp; Kepuasan Pengguna Lulusan</h1>
    <div class="sub">
        Program Studi <?= esc($prodi['nama']) ?> (<?= esc($prodi['jenjang']) ?>)<?= $prodi['pt_nama'] ? ' &middot; ' . esc($prodi['pt_nama']) : '' ?><br>
        Dicetak: <?= esc($tanggal) ?>
    </div>

    <h2>A. Ringkasan Tracer Study</h2>
    <?php if (empty($ts)): ?>
        <p class="muted">Belum ada data tracer study.</p>
    <?php else: ?>
    <table class="kpi">
        <tr>
            <td><div class="box"><div class="num"><?= (int) $ts['total'] ?></div><div class="lbl">Responden (dari <?= $totalLulus ?> lulusan)</div></div></td>
            <td><div class="box"><div class="num"><?= $rate ?>%</div><div class="lbl">Response rate</div></div></td>
            <td><div class="box"><div class="num"><?= $ts['avg_masa_tunggu'] !== null ? $ts['avg_masa_tunggu'] : '-' ?></div><div class="lbl">Rata-rata masa tunggu (bln)</div></div></td>
            <td><div class="box"><div class="num"><?= $ts['pct_sesuai'] ?>%</div><div class="lbl">Kesesuaian bidang kerja</div></div></td>
        </tr>
        <tr>
            <td><div class="box"><div class="num"><?= $ts['pct_multinasional'] ?>%</div><div class="lbl">Multinasional/Internasional</div></div></td>
            <td><div class="box"><div class="num"><?= $ts['pct_wirausaha'] ?>%</div><div class="lbl">Wirausaha</div></div></td>
            <td><div class="box"><div class="num"><?= $ts['pct_lanjut_studi'] ?>%</div><div class="lbl">Lanjut studi</div></div></td>
            <td></td>
        </tr>
    </table>
    <?php endif ?>

    <h2>B. Detail Responden (Tabel 2.B.4 &amp; 2.B.5)</h2>
    <?php if (empty($tracerList)): ?>
        <p class="muted">Belum ada responden tracer study.</p>
    <?php else: ?>
    <table>
        <thead><tr>
            <th>No</th><th>NIM</th><th>Nama</th><th>Masa Tunggu (bln)</th><th>Kesesuaian</th><th>Tingkat</th><th>Perusahaan</th><th>Lanjut Studi</th>
        </tr></thead>
        <tbody>
        <?php foreach ($tracerList as $i => $t): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($t['nim']) ?></td>
                <td><?= esc($t['nama_mhs']) ?></td>
                <td><?= $t['masa_tunggu_bulan'] !== null ? (int) $t['masa_tunggu_bulan'] : '-' ?></td>
                <td><?= esc($kesLabel[$t['kesesuaian_bidang_kerja']] ?? '-') ?></td>
                <td><?= esc(ucfirst($t['tingkat_pekerjaan'] ?? '-')) ?></td>
                <td><?= esc($t['nama_perusahaan'] ?? '-') ?></td>
                <td><?= !empty($t['lanjut_studi']) ? 'Ya' : '-' ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>

    <h2>C. Ringkasan Kepuasan Pengguna Lulusan (Tabel 2.B.6)</h2>
    <?php if (empty($kepSum)): ?>
        <p class="muted">Belum ada data survei kepuasan.</p>
    <?php else: ?>
    <p class="sub">Responden: <strong><?= (int) $kepSum['total'] ?></strong> &middot; Rata-rata keseluruhan (skala 1&ndash;4):
        <strong><?= $kepSum['avg_all'] !== null ? number_format($kepSum['avg_all'], 2) : '-' ?></strong></p>
    <table>
        <thead><tr><th>Aspek Penilaian</th><th>Rata-rata (1&ndash;4)</th></tr></thead>
        <tbody>
        <?php foreach ($aspek as $k => $label): $v = $kepSum['avg'][$k] ?? null; ?>
            <tr><td><?= esc($label) ?></td><td><?= $v !== null ? number_format($v, 2) : '-' ?></td></tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php endif ?>

    <div class="foot">Dihasilkan otomatis oleh <?= esc(env('app.namaAplikasi', 'KAPRODI')) ?> untuk keperluan akreditasi LAM INFOKOM.</div>
</body>
</html>
