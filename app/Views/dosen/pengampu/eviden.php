<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-shield-check"></i> Eviden Surat Tugas Mengajar</h4>
    <div class="d-flex gap-2">
        <a href="<?= site_url('dosen/pengampu') ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Penugasan Mengajar</a>
        <a href="<?= site_url('dosen/pengampu/eviden/ekspor' . ($taId ? '?ta_id=' . $taId : '')) ?>" class="btn btn-sm btn-success"><i class="bi bi-file-earmark-spreadsheet"></i> Ekspor CSV</a>
    </div>
</div>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Daftar Surat Tugas Mengajar yang sudah <strong>diterbitkan (memiliki nomor surat)</strong>. URL Eviden adalah halaman validasi publik yang dapat dipindai dari QR pada PDF — bisa dipakai sebagai bukti dokumen di borang/LKPS.</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small mb-0 fw-semibold">Tahun Akademik</label>
                <select name="ta_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="0">— Semua TA —</option>
                    <?php foreach ($taList as $ta): ?>
                        <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-auto">
                <span class="badge bg-secondary"><?= count($rows) ?> dokumen</span>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Dosen</th>
                        <th>NIDN</th>
                        <th>TA</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal</th>
                        <th class="text-center">Jml MK</th>
                        <th class="text-center">Total SKS</th>
                        <th>URL Eviden</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($rows)): ?>
                    <tr><td colspan="10" class="text-center text-muted py-4">Belum ada Surat Tugas yang diterbitkan.</td></tr>
                <?php else: foreach ($rows as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($r['nama_lengkap']) ?></td>
                        <td><small><?= esc($r['nidn'] ?? '-') ?></small></td>
                        <td><small><?= esc($r['ta_kode']) ?><?= $r['is_ts'] ? ' <span class="badge bg-primary" style="font-size:9px">TS</span>' : '' ?></small></td>
                        <td><strong><?= esc($r['nomor']) ?></strong></td>
                        <td><small><?= esc($r['tanggal'] ?? '-') ?></small></td>
                        <td class="text-center"><?= $r['jml_mk'] ?></td>
                        <td class="text-center"><?= $r['total_sks'] ?></td>
                        <td style="max-width:240px">
                            <div class="input-group input-group-sm">
                                <input type="text" id="ev<?= $r['id'] ?>" class="form-control form-control-sm" readonly value="<?= esc($r['eviden_url'], 'attr') ?>" style="font-size:10px" onclick="this.select()">
                                <button class="btn btn-outline-secondary btn-sm" type="button" onclick="salinLink('ev<?= $r['id'] ?>', this)" title="Salin"><i class="bi bi-clipboard"></i></button>
                            </div>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="<?= esc($r['eviden_url'], 'attr') ?>" target="_blank" class="btn btn-sm btn-outline-info py-0 px-1" title="Buka halaman validasi"><i class="bi bi-box-arrow-up-right"></i></a>
                            <a href="<?= site_url("dosen/pengampu/{$r['dosen_id']}/surat?ta_id={$r['tahun_akademik_id']}") ?>" class="btn btn-sm btn-outline-primary py-0 px-1" title="Edit surat"><i class="bi bi-pencil"></i></a>
                        </td>
                    </tr>
                <?php endforeach; endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function salinLink(id, btn){
    var inp=document.getElementById(id); if(!inp) return; inp.select();
    if(navigator.clipboard){ navigator.clipboard.writeText(inp.value).then(()=>flash(btn)); }
    else{ document.execCommand('copy'); flash(btn); }
}
function flash(btn){
    var o=btn.innerHTML;
    btn.innerHTML='<i class="bi bi-check"></i>';
    btn.classList.add('btn-success'); btn.classList.remove('btn-outline-secondary');
    setTimeout(()=>{ btn.innerHTML=o; btn.classList.remove('btn-success'); btn.classList.add('btn-outline-secondary'); }, 1200);
}
</script>

<?= $this->endSection() ?>
