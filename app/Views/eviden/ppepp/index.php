<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-clipboard2-check"></i> Eviden PPEPP &mdash; Persiapan Visitasi</h4>
    <a href="<?= esc($visitasiUrl, 'attr') ?>" target="_blank" class="btn btn-sm btn-outline-success"><i class="bi bi-box-arrow-up-right"></i> Pratinjau Halaman Visitasi</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="card mb-3">
    <div class="card-body py-2">
        <div class="small fw-semibold mb-1"><i class="bi bi-link-45deg"></i> URL Visitasi (bagikan ke asesor)</div>
        <div class="input-group input-group-sm">
            <input type="text" id="vstLink" class="form-control" readonly value="<?= esc($visitasiUrl, 'attr') ?>" onclick="this.select()">
            <button class="btn btn-outline-secondary" type="button" onclick="salinLink('vstLink', this)"><i class="bi bi-clipboard"></i> Salin</button>
        </div>
        <div class="form-text mt-1">Link ini dapat dibuka tanpa login. Asesor akan melihat matriks PPEPP lengkap per kriteria/butir untuk prodi Anda.</div>
    </div>
</div>

<?php if (empty($grouped)): ?>
    <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Belum ada instrumen LAM aktif untuk prodi Anda. Tetapkan dulu di menu Instrumen Akreditasi.</div>
<?php else: foreach ($grouped as $iid => $instr): ?>
    <div class="card mb-3">
        <div class="card-header bg-primary text-white fw-semibold"><i class="bi bi-bookmark-star"></i> <?= esc($instr['nama']) ?></div>
        <div class="card-body p-0">
            <?php foreach ($instr['kriteria'] as $kid => $kr): ?>
                <div class="border-bottom px-3 py-2 bg-light fw-semibold">
                    <span class="badge bg-secondary"><?= esc($kr['kode']) ?></span> <?= esc($kr['nama']) ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:80px">Kode</th>
                                <th>Judul Butir</th>
                                <th class="text-center" style="width:90px">Jenis</th>
                                <th class="text-center" style="width:120px">PPEPP</th>
                                <th class="text-end" style="width:120px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($kr['butir'] as $b): $p = $progress[(int)$b['id']] ?? 0; ?>
                            <tr>
                                <td><small><?= esc($b['butir_kode']) ?></small></td>
                                <td><?= esc($b['judul']) ?></td>
                                <td class="text-center"><small class="text-muted"><?= esc(ucfirst($b['jenis_ipo'])) ?></small></td>
                                <td class="text-center">
                                    <div class="progress" style="height:8px"><div class="progress-bar <?= $p == 5 ? 'bg-success' : ($p > 0 ? 'bg-warning' : 'bg-secondary') ?>" style="width:<?= ($p/5)*100 ?>%"></div></div>
                                    <small><?= $p ?>/5</small>
                                </td>
                                <td class="text-end">
                                    <a href="<?= site_url("eviden/ppepp/{$b['id']}/edit") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil-square"></i> Isi PPEPP</a>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endforeach; endif ?>

<script>
function salinLink(id, btn){var inp=document.getElementById(id);if(!inp)return;inp.select();if(navigator.clipboard){navigator.clipboard.writeText(inp.value).then(()=>flash(btn));}else{document.execCommand('copy');flash(btn);}}
function flash(btn){var o=btn.innerHTML;btn.innerHTML='<i class="bi bi-check"></i> Tersalin';btn.classList.add('btn-success');btn.classList.remove('btn-outline-secondary');setTimeout(()=>{btn.innerHTML=o;btn.classList.remove('btn-success');btn.classList.add('btn-outline-secondary');},1500);}
</script>

<?= $this->endSection() ?>
