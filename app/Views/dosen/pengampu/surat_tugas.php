<?php
/** @var array $kop @var ?string $logo @var array $dosen @var string $namaDosen @var array $ta @var array $list @var array $kategori */
helper('text');
$uppsLabel = trim(ucfirst((string) ($kop['upps_jenis'] ?? '')) . ' ' . ($kop['upps_nama'] ?? ''));
$alamat = trim(implode(', ', array_filter([$kop['pt_alamat'] ?? '', $kop['pt_kota'] ?? '', $kop['pt_provinsi'] ?? ''])));
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { font-family: Helvetica, Arial, sans-serif; }
    body { font-size: 11.5px; color: #000; margin: 0; }
    .kop { border-bottom: 3px double #000; padding-bottom: 6px; margin-bottom: 14px; }
    .kop table { width: 100%; border-collapse: collapse; }
    .kop .logo { width: 75px; text-align: center; vertical-align: middle; }
    .kop .logo img { width: 64px; height: auto; }
    .kop .txt { text-align: center; vertical-align: middle; }
    .kop .pt { font-size: 16px; font-weight: bold; text-transform: uppercase; letter-spacing: .3px; }
    .kop .upps { font-size: 13px; font-weight: bold; text-transform: uppercase; }
    .kop .prodi { font-size: 12px; }
    .kop .addr { font-size: 9.5px; margin-top: 2px; }
    h1.title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin: 6px 0 0; }
    .nomor { text-align: center; font-size: 11.5px; margin: 2px 0 16px; }
    p { margin: 6px 0; text-align: justify; line-height: 1.45; }
    table.mk { width: 100%; border-collapse: collapse; margin: 10px 0; }
    table.mk th, table.mk td { border: 1px solid #555; padding: 4px 6px; font-size: 10.5px; }
    table.mk th { background: #eee; text-align: center; }
    table.mk td.c { text-align: center; }
    .ttd { width: 100%; margin-top: 26px; }
    .ttd td { vertical-align: top; font-size: 11.5px; }
    .ttd .sign { width: 45%; }
    .sp { height: 60px; }
    .nm { font-weight: bold; text-decoration: underline; }
</style>
</head>
<body>

<div class="kop">
    <table>
        <tr>
            <?php if ($logo): ?><td class="logo"><img src="<?= $logo ?>" alt=""></td><?php endif ?>
            <td class="txt">
                <?php if (!empty($kop['pt_nama'])): ?><div class="pt"><?= esc($kop['pt_nama']) ?></div><?php endif ?>
                <?php if ($uppsLabel !== ''): ?><div class="upps"><?= esc($uppsLabel) ?></div><?php endif ?>
                <div class="prodi">Program Studi <?= esc($kop['prodi_nama'] ?? '') ?><?= !empty($kop['jenjang']) ? ' (' . esc($kop['jenjang']) . ')' : '' ?></div>
                <?php if ($alamat !== ''): ?><div class="addr"><?= esc($alamat) ?><?= !empty($kop['pt_website']) ? ' &middot; ' . esc($kop['pt_website']) : '' ?></div><?php endif ?>
            </td>
            <?php if ($logo): ?><td class="logo">&nbsp;</td><?php endif ?>
        </tr>
    </table>
</div>

<h1 class="title">Surat Tugas Mengajar</h1>
<div class="nomor">Nomor: <?= esc($nomor) ?></div>

<p>Yang bertanda tangan di bawah ini, Ketua Program Studi <?= esc($kop['prodi_nama'] ?? '') ?>, dengan ini menugaskan kepada:</p>

<table style="margin-left:20px">
    <tr><td style="width:140px">Nama</td><td>: <strong><?= esc($namaDosen) ?></strong></td></tr>
    <?php if (!empty($dosen['nidn'])): ?><tr><td>NIDN</td><td>: <?= esc($dosen['nidn']) ?></td></tr><?php endif ?>
    <?php if (!empty($dosen['nip'])): ?><tr><td>NIP</td><td>: <?= esc($dosen['nip']) ?></td></tr><?php endif ?>
    <?php if (!empty($dosen['jabatan_akademik'])): ?><tr><td>Jabatan Akademik</td><td>: <?= esc($dosen['jabatan_akademik']) ?></td></tr><?php endif ?>
</table>

<p>untuk melaksanakan tugas mengajar mata kuliah berikut pada Tahun Akademik <strong><?= esc($ta['kode'] ?? '-') ?></strong>:</p>

<table class="mk">
    <thead>
        <tr><th style="width:28px">No</th><th>Mata Kuliah</th><th>Kategori</th><th style="width:45px">SKS</th><th style="width:45px">Kelas</th><th style="width:55px">Jml SKS</th></tr>
    </thead>
    <tbody>
        <?php $g = 0; foreach ($list as $i => $r): $g += (float) $r['total_sks']; ?>
        <tr>
            <td class="c"><?= $i + 1 ?></td>
            <td><?= esc($r['nama_mk']) ?></td>
            <td><?= esc($kategori[$r['kategori']] ?? $r['kategori']) ?></td>
            <td class="c"><?= (float) $r['sks'] ?></td>
            <td class="c"><?= (int) $r['jumlah_kelas'] ?></td>
            <td class="c"><?= (float) $r['total_sks'] ?></td>
        </tr>
        <?php endforeach ?>
        <tr><td class="c" colspan="5" style="text-align:right;font-weight:bold">Total SKS</td><td class="c" style="font-weight:bold"><?= $g ?></td></tr>
    </tbody>
</table>

<?php if (!empty($dasar)): ?>
<p><strong>Dasar:</strong> <?= nl2br(esc($dasar)) ?></p>
<?php endif ?>

<p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab. Atas perhatian dan kerja samanya diucapkan terima kasih.</p>

<table class="ttd">
    <tr>
        <td>&nbsp;</td>
        <td class="sign">
            <?= esc($kota) ?>, <?= esc($tanggal) ?><br>
            Ketua Program Studi,
            <div class="sp"></div>
            <span class="nm"><?= esc($signerNama !== '' ? $signerNama : '..............................') ?></span><br>
            <?= $signerNip !== '' ? 'NIP/NIDN. ' . esc($signerNip) : '&nbsp;' ?>
        </td>
    </tr>
</table>

</body>
</html>
