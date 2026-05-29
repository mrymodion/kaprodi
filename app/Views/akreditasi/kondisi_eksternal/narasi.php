<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$KAT = \App\Models\KondisiEksternalModel::KATEGORI;
$DMP = \App\Models\KondisiEksternalModel::DAMPAK_LABELS;
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-file-text"></i> Narasi LED &mdash; <span class="badge bg-primary">A</span> Kondisi Eksternal</h4>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</button>
        <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Input</a>
    </div>
</div>

<div class="alert alert-info small no-print">
    <i class="bi bi-lightbulb"></i> Halaman ini menyusun narasi terstruktur dari data input Anda. Pada <strong>Sprint 3</strong>, akan ada tombol <em>"Draft via AI"</em> yang menyempurnakan narasi ini menjadi paragraf lebih mengalir untuk LED. Untuk sekarang, Anda dapat menyalin teks di bawah ke dokumen LED.
</div>

<style>
@media print { .no-print { display: none !important; } }
.narasi-blok { border-left: 4px solid #0d6efd; padding-left: 14px; margin-bottom: 22px; }
.narasi-blok h5 { color: #0d6efd; }
.narasi-item { margin-bottom: 10px; }
</style>

<div class="card">
    <div class="card-body">
        <h4 class="mb-3 text-center">A. Kondisi Eksternal</h4>

        <?php
        $totalEntries = 0;
        foreach ($grouped as $entries) $totalEntries += count($entries);
        if ($totalEntries === 0): ?>
            <div class="alert alert-warning text-center">Belum ada entri Kondisi Eksternal. <a href="<?= site_url('akreditasi/kondisi-eksternal') ?>">Mulai input</a>.</div>
        <?php else: ?>

        <p>Analisis kondisi eksternal Program Studi disusun berdasarkan kerangka PESTEL (Politik, Ekonomi, Sosial, Teknologi, Hukum), kondisi pasar (demografi, pasar kerja, kompetitor), serta identifikasi peluang dan ancaman. Total <strong><?= $totalEntries ?></strong> faktor eksternal teridentifikasi sebagai berikut.</p>

        <?php
        // Group ke 4 sub-bagian: PESTEL, Pasar, SWOT-Ext
        $subSections = [
            'A.1 Analisis PESTEL'                  => ['politik','ekonomi','sosial','teknologi','hukum'],
            'A.2 Analisis Pasar & Kompetisi'       => ['demografi','pasar_kerja','kompetitor'],
            'A.3 Peluang dan Ancaman (SWOT-Ekst)'  => ['peluang','ancaman'],
        ];
        foreach ($subSections as $heading => $kategoriList):
            $subTotal = 0;
            foreach ($kategoriList as $k) $subTotal += count($grouped[$k] ?? []);
            if ($subTotal === 0) continue;
        ?>
        <h5 class="mt-4 mb-3 fw-bold"><?= esc($heading) ?></h5>

        <?php foreach ($kategoriList as $k): $entries = $grouped[$k] ?? []; if (empty($entries)) continue; $info = $KAT[$k]; ?>
            <div class="narasi-blok">
                <h5><?= esc($info['label']) ?></h5>
                <p class="text-muted small"><?= esc($info['desc']) ?></p>
                <?php foreach ($entries as $r): ?>
                    <div class="narasi-item">
                        <strong><?= esc($r['judul']) ?><?= $r['tahun_relevansi'] ? ' (' . $r['tahun_relevansi'] . ')' : '' ?>.</strong>
                        <?= nl2br(esc($r['deskripsi'])) ?>
                        <em>Dampak terhadap prodi: <?= $DMP[$r['dampak']]['label'] ?>.</em>
                        <?php if (!empty($r['respons_prodi'])): ?> <em>Respons prodi:</em> <?= nl2br(esc($r['respons_prodi'])) ?><?php endif ?>
                        <?php if (!empty($r['sumber_referensi'])): ?> <small class="text-muted">[Sumber: <?= esc($r['sumber_referensi']) ?><?= !empty($r['link_bukti']) ? '; bukti tersedia' : '' ?>]</small><?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endforeach ?>
        <?php endforeach ?>

        <h5 class="mt-4 mb-3 fw-bold">A.4 Ringkasan Strategis</h5>
        <p>
            Berdasarkan analisis di atas, prodi mengidentifikasi
            <strong><?= count($grouped['peluang']) ?> peluang</strong> dan
            <strong><?= count($grouped['ancaman']) ?> ancaman</strong>
            sebagai dasar penyusunan strategi pengembangan ke depan. Faktor-faktor eksternal ini menjadi pertimbangan dalam evaluasi VMTS, perumusan kurikulum, kerjasama, dan pengembangan SDM dosen.
        </p>
        <?php endif ?>
    </div>
</div>
<?= $this->endSection() ?>
