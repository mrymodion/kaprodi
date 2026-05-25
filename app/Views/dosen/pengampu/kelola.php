<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-easel"></i> MK Diampu &mdash; <?= esc($namaDosen) ?></h4>
    <div>
        <a href="<?= site_url('dosen/pengampu?ta_id=' . $taId) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Daftar</a>
        <a href="<?= site_url("dosen/pengampu/{$dosen['id']}/surat?ta_id={$taId}") ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-pdf"></i> Surat Tugas</a>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="mb-3" style="max-width:320px">
    <label class="form-label small mb-0 fw-semibold">Tahun Akademik</label>
    <select class="form-select form-select-sm" onchange="location.href='<?= site_url("dosen/pengampu/{$dosen['id']}/kelola") ?>?ta_id='+this.value">
        <option value="">— Pilih —</option>
        <?php foreach ($taList as $ta): ?>
            <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
        <?php endforeach ?>
    </select>
</div>

<?php if ($taId): ?>
<div class="card mb-3">
    <div class="card-header bg-success text-white fw-semibold">Tambah Mata Kuliah Diampu</div>
    <div class="card-body">
        <form method="post" action="<?= site_url('dosen/pengampu/store') ?>" class="row g-2">
            <?= csrf_field() ?>
            <input type="hidden" name="tahun_akademik_id" value="<?= $taId ?>">
            <input type="hidden" name="dosen_id" value="<?= $dosen['id'] ?>">
            <div class="col-md-3">
                <label class="form-label small mb-0">Kategori <span class="text-danger">*</span></label>
                <select name="kategori" id="kategori" class="form-select form-select-sm">
                    <?php foreach ($kategori as $k => $v): ?><option value="<?= $k ?>"><?= esc($v) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-0">Pilih dari MK Prodi (PS sendiri)</label>
                <select name="mata_kuliah_id" id="mkSelect" class="form-select form-select-sm">
                    <option value="" data-sks="0" data-nama="">— manual / MK luar prodi —</option>
                    <?php foreach ($mkList as $mk): $tot = $mk['sks_teori'] + $mk['sks_praktek'] + $mk['sks_lapangan']; ?>
                        <option value="<?= $mk['id'] ?>" data-sks="<?= $tot ?>" data-nama="<?= esc($mk['kode'] . ' - ' . $mk['nama'], 'attr') ?>"><?= esc($mk['kode'] . ' - ' . $mk['nama']) ?> (<?= $tot ?> SKS)</option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-0">Nama Mata Kuliah <span class="text-danger">*</span></label>
                <input type="text" name="nama_mk" id="namaMk" class="form-control form-control-sm" placeholder="mis. Basis Data">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">SKS <span class="text-danger">*</span></label>
                <input type="number" name="sks" id="sks" class="form-control form-control-sm" min="0" max="20" step="0.5" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-0">Jumlah Kelas</label>
                <input type="number" name="jumlah_kelas" class="form-control form-control-sm" min="1" value="1">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-sm btn-primary w-100"><i class="bi bi-plus-circle"></i> Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0 align-middle">
                <thead class="table-light"><tr><th>#</th><th>Kategori</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Kelas</th><th class="text-center">Total SKS</th><th></th></tr></thead>
                <tbody>
                <?php if (empty($list)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada mata kuliah diampu pada TA ini.</td></tr>
                <?php else: $grand = 0; foreach ($list as $i => $r): $grand += (float) $r['total_sks']; ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><small><?= esc($kategori[$r['kategori']] ?? $r['kategori']) ?></small></td>
                        <td><?= esc($r['nama_mk']) ?></td>
                        <td class="text-center"><?= (float) $r['sks'] ?></td>
                        <td class="text-center"><?= (int) $r['jumlah_kelas'] ?></td>
                        <td class="text-center fw-semibold"><?= (float) $r['total_sks'] ?></td>
                        <td class="text-end">
                            <form method="post" action="<?= site_url("dosen/pengampu/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                    <tr class="table-light fw-bold"><td colspan="5" class="text-end">Total SKS Pendidikan</td><td class="text-center"><?= $grand ?></td><td></td></tr>
                <?php endif ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
(function () {
    var sel = document.getElementById('mkSelect');
    if (!sel) return;
    var nama = document.getElementById('namaMk'), sks = document.getElementById('sks'), kat = document.getElementById('kategori');
    sel.addEventListener('change', function () {
        var opt = sel.options[sel.selectedIndex];
        if (sel.value) {
            nama.value = opt.getAttribute('data-nama'); nama.readOnly = true;
            sks.value = opt.getAttribute('data-sks'); sks.readOnly = true;
            kat.value = 'ps_sendiri';
        } else {
            nama.value = ''; nama.readOnly = false;
            sks.value = 0; sks.readOnly = false;
        }
    });
})();
</script>
<?php endif ?>

<?= $this->endSection() ?>
