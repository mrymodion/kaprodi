<?php
/** @var array $kop @var ?string $logo @var ?string $kopImage @var ?string $kopFooter @var array $dosen @var string $namaDosen @var array $ta @var array $list @var array $kategori */
$uppsLabel = trim(ucfirst((string) ($kop['upps_jenis'] ?? '')) . ' ' . ($kop['upps_nama'] ?? ''));
$alamat = trim(implode(', ', array_filter([$kop['pt_alamat'] ?? '', $kop['pt_kota'] ?? '', $kop['pt_provinsi'] ?? ''])));
$prodiNama = trim(($kop['prodi_nama'] ?? '') . (!empty($kop['jenjang']) ? ' ' . $kop['jenjang'] : ''));
$ptNama = $kop['pt_nama'] ?? '';
$sem = strtolower((string) ($ta['semester'] ?? ''));
$semLabel = in_array($sem, ['ganjil', 'genap'], true) ? 'semester ' . ucfirst($sem) . ' ' : '';
$useImg = !empty($kopImage);                 // mode kop gambar (banner full-width)
$hasFooter = !empty($kopFooter);
$padBottom = $hasFooter ? 90 : 24;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    * { font-family: Helvetica, Arial, sans-serif; }
<?php if ($useImg): ?>
    @page { margin: 0; }
    body { font-size: 12px; color: #000; margin: 0; padding-bottom: <?= $padBottom ?>px; }
    .kop-atas { width: 100%; }
    .kop-atas img { width: 100%; height: auto; display: block; }
    .kop-bawah { position: fixed; bottom: 0; left: 0; right: 0; width: 100%; }
    .kop-bawah img { width: 100%; height: auto; display: block; }
    .content { padding: 10px 40px 0 40px; }
<?php else: ?>
    @page { margin: 1.8cm; }
    body { font-size: 12px; color: #000; margin: 0; }
    .content { }
    .kop { border-bottom: 3px double #000; padding-bottom: 6px; margin-bottom: 16px; }
    .kop table { width: 100%; border-collapse: collapse; }
    .kop .logo { width: 80px; text-align: center; vertical-align: middle; }
    .kop .logo img { width: 70px; height: auto; }
    .kop .txt { text-align: center; vertical-align: middle; }
    .kop .pt { font-size: 17px; font-weight: bold; text-transform: uppercase; }
    .kop .upps { font-size: 13px; font-weight: bold; text-transform: uppercase; }
    .kop .prodi { font-size: 12px; }
    .kop .addr { font-size: 9.5px; margin-top: 2px; }
<?php endif ?>
    h1.title { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; text-decoration: underline; margin: 6px 0 0; }
    .nomor { text-align: center; font-size: 12px; margin: 2px 0 18px; }
    p { margin: 8px 0; text-align: justify; line-height: 1.5; }
    table.mk { width: 100%; border-collapse: collapse; margin: 10px 0; }
    table.mk th, table.mk td { border: 1px solid #000; padding: 5px 6px; font-size: 11px; }
    table.mk th { background: #eee; text-align: center; }
    table.mk td.c { text-align: center; }
    .ttd { width: 100%; margin-top: 28px; }
    .ttd td { vertical-align: bottom; font-size: 12px; }
    .ttd .sign { width: 48%; vertical-align: top; }
    .ttd .qr { width: 52%; }
    .ttd .qr img { width: 78px; height: 78px; }
    .ttd .qr .cap { font-size: 8.5px; color: #333; line-height: 1.3; }
    .sp { height: 64px; }
    .nm { font-weight: bold; text-decoration: underline; }
</style>
</head>
<body>

<?php if ($useImg): ?>
    <div class="kop-atas"><img src="<?= $kopImage ?>" alt=""></div>
    <?php if ($hasFooter): ?><div class="kop-bawah"><img src="<?= $kopFooter ?>" alt=""></div><?php endif ?>
<?php endif ?>

<div class="content">

<?php if (!$useImg): ?>
<div class="kop">
    <table>
        <tr>
            <?php if ($logo): ?><td class="logo"><img src="<?= $logo ?>" alt=""></td><?php endif ?>
            <td class="txt">
                <?php if ($ptNama !== ''): ?><div class="pt"><?= esc($ptNama) ?></div><?php endif ?>
                <?php if ($uppsLabel !== ''): ?><div class="upps"><?= esc($uppsLabel) ?></div><?php endif ?>
                <div class="prodi">Program Studi <?= esc($prodiNama) ?></div>
                <?php if ($alamat !== ''): ?><div class="addr"><?= esc($alamat) ?><?= !empty($kop['pt_website']) ? ' &middot; ' . esc($kop['pt_website']) : '' ?></div><?php endif ?>
            </td>
            <?php if ($logo): ?><td class="logo">&nbsp;</td><?php endif ?>
        </tr>
    </table>
</div>
<?php endif ?>

<h1 class="title">Surat Tugas Mengajar</h1>
<div class="nomor">Nomor: <?= esc($nomor) ?></div>

<p>Ketua Program Studi <?= esc($prodiNama) ?> <?= esc($ptNama) ?>, menugaskan kepada dosen tersebut di bawah ini untuk menjadi Dosen Pengampu mata kuliah di Program Studi <?= esc($prodiNama) ?>, pada <?= esc($semLabel) ?>Tahun Akademik <?= esc($ta['kode'] ?? '-') ?> dengan rincian sebagai berikut:</p>

<table class="mk">
    <thead>
        <tr><th style="width:26px">No.</th><th>Nama Dosen</th><th>Mata Kuliah</th><th style="width:90px">Kode Mata Kuliah</th><th style="width:38px">SKS</th><th style="width:55px">Jumlah Kelas</th></tr>
    </thead>
    <tbody>
        <?php foreach ($list as $i => $r): ?>
        <tr>
            <td class="c"><?= $i + 1 ?>.</td>
            <td><?= esc($namaDosen) ?></td>
            <td><?= esc($r['nama_mk']) ?></td>
            <td class="c"><?= esc($r['kode_mk'] ?? '-') ?></td>
            <td class="c"><?= (float) $r['sks'] ?></td>
            <td class="c"><?= (int) $r['jumlah_kelas'] ?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<?php if (!empty($dasar)): ?>
<p><strong>Dasar:</strong> <?= nl2br(esc($dasar)) ?></p>
<?php endif ?>

<p>Kepadanya diberikan honorarium menurut peraturan yang berlaku di <?= esc($ptNama) ?>.</p>
<p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab dan sebagaimana mestinya.</p>

<table class="ttd">
    <tr>
        <td class="qr">
            <?php if (!empty($qr)): ?>
            <table><tr>
                <td style="width:78px;vertical-align:top"><img src="<?= $qr ?>" alt="QR validasi"></td>
                <td class="cap" style="vertical-align:top;padding-left:6px">Pindai QR untuk memvalidasi keaslian dokumen ini secara daring.</td>
            </tr></table>
            <?php endif ?>
        </td>
        <td class="sign">
            <?= esc($kota) ?>, <?= esc($tanggal) ?><br>
            Ketua Program Studi <?= esc($prodiNama) ?>,
            <div class="sp"></div>
            <span class="nm"><?= esc($signerNama !== '' ? $signerNama : '..............................') ?></span><br>
            <?= $signerNip !== '' ? 'NIP/NIDN. ' . esc($signerNip) : '&nbsp;' ?>
        </td>
    </tr>
</table>

</div>
</body>
</html>
