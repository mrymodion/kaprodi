<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-earmark-pdf"></i> Surat Tugas Mengajar</h4>
    <a href="<?= site_url('dosen/pengampu?ta_id=' . $taId) ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Daftar</a>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Data Surat &mdash; <?= esc($namaDosen) ?></div>
            <div class="card-body">
                <?php if (empty($list)): ?>
                    <div class="alert alert-warning small mb-0"><i class="bi bi-exclamation-triangle"></i> Dosen ini belum memiliki mata kuliah diampu pada TA terpilih. <a href="<?= site_url("dosen/pengampu/{$dosen['id']}/kelola?ta_id={$taId}") ?>">Input MK dulu</a>.</div>
                <?php else: ?>
                <form method="post" action="<?= site_url("dosen/pengampu/{$dosen['id']}/surat") ?>" target="_blank">
                    <?= csrf_field() ?>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Tahun Akademik</label>
                        <select name="tahun_akademik_id" class="form-select form-select-sm" required onchange="location.href='<?= site_url("dosen/pengampu/{$dosen['id']}/surat") ?>?ta_id='+this.value">
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Nomor Surat</label>
                        <input type="text" name="nomor" class="form-control form-control-sm" value="<?= esc($meta['nomor'] ?? '', 'attr') ?>" placeholder="mis. 123/ST/PRODI/<?= date('Y') ?>">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Dasar / Pertimbangan (opsional)</label>
                        <textarea name="dasar" class="form-control form-control-sm" rows="2" placeholder="mis. Kalender akademik & beban kerja dosen semester berjalan"><?= esc($meta['dasar'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-7">
                            <label class="form-label small mb-0">Kota</label>
                            <input type="text" name="kota" class="form-control form-control-sm" value="<?= esc($meta['kota'] ?? ($kop['pt_kota'] ?? ''), 'attr') ?>">
                        </div>
                        <div class="col-5">
                            <label class="form-label small mb-0">Tanggal</label>
                            <input type="text" name="tanggal" class="form-control form-control-sm" value="<?= esc($meta['tanggal'] ?? date('d-m-Y'), 'attr') ?>">
                        </div>
                    </div>
                    <hr>
                    <div class="mb-2">
                        <label class="form-label small mb-0">Penandatangan (Kaprodi)</label>
                        <input type="text" name="signer_nama" class="form-control form-control-sm" value="<?= esc($meta['signer_nama'] ?? ($signer['nama'] ?? ''), 'attr') ?>" placeholder="Nama kaprodi">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small mb-0">NIP / NIDN Penandatangan</label>
                        <input type="text" name="signer_nip" class="form-control form-control-sm" value="<?= esc($meta['signer_nip'] ?? ($signer['nip'] ?? ''), 'attr') ?>">
                    </div>
                    <?php if (!empty($meta)): ?>
                    <div class="alert alert-info small py-1 mb-2"><i class="bi bi-info-circle"></i> Surat untuk TA ini sudah pernah diterbitkan. Field di atas otomatis terisi dari penerbitan terakhir; ubah jika perlu.</div>
                    <?php endif ?>
                    <?php if (!empty($evidenUrl)): ?>
                    <div class="border rounded p-2 mb-2 bg-light">
                        <div class="small fw-semibold mb-1"><i class="bi bi-link-45deg"></i> URL Eviden Akreditasi (validasi publik)</div>
                        <div class="input-group input-group-sm">
                            <input type="text" id="evdLink" class="form-control" readonly value="<?= esc($evidenUrl, 'attr') ?>" onclick="this.select()">
                            <button class="btn btn-outline-secondary" type="button" onclick="salinLink('evdLink', this)"><i class="bi bi-clipboard"></i> Salin</button>
                        </div>
                        <div class="form-text mt-1">Tempel link ini di kolom bukti pada borang/LKPS. Asesor dapat scan QR atau klik link untuk verifikasi.</div>
                    </div>
                    <?php endif ?>
                    <div class="d-flex gap-2">
                        <button type="submit" formaction="<?= site_url("dosen/pengampu/{$dosen['id']}/surat/simpan") ?>" formtarget="_self" class="btn btn-primary">
                            <i class="bi bi-check"></i> Simpan
                        </button>
                        <button class="btn btn-success">
                            <i class="bi bi-download"></i> Simpan &amp; Unduh PDF
                        </button>
                    </div>
                    <div class="form-text mt-1"><i class="bi bi-info-circle"></i> <strong>Simpan</strong>: hanya menyimpan nomor & data surat (dosen bisa langsung unduh sendiri). <strong>Simpan &amp; Unduh</strong>: sekaligus mengunduh PDF.</div>
                </form>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Mata Kuliah Diampu</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light"><tr><th>Kode</th><th>Mata Kuliah</th><th class="text-center">SKS</th><th class="text-center">Kelas</th><th class="text-center">Total</th></tr></thead>
                    <tbody>
                    <?php if (empty($list)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">—</td></tr>
                    <?php else: $g = 0; foreach ($list as $r): $g += (float) $r['total_sks']; ?>
                        <tr><td><small><?= esc($r['kode_mk'] ?? '-') ?></small></td><td><?= esc($r['nama_mk']) ?></td><td class="text-center"><?= (float) $r['sks'] ?></td><td class="text-center"><?= (int) $r['jumlah_kelas'] ?></td><td class="text-center"><?= (float) $r['total_sks'] ?></td></tr>
                    <?php endforeach ?>
                        <tr class="table-light fw-bold"><td class="text-end" colspan="4">Total SKS</td><td class="text-center"><?= $g ?></td></tr>
                    <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function salinLink(id, btn) {
    var inp = document.getElementById(id);
    if (!inp) return;
    inp.select();
    if (navigator.clipboard) {
        navigator.clipboard.writeText(inp.value).then(function(){ flashSalin(btn); });
    } else {
        document.execCommand('copy'); flashSalin(btn);
    }
}
function flashSalin(btn){
    var old = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-check"></i> Tersalin';
    btn.classList.add('btn-success'); btn.classList.remove('btn-outline-secondary');
    setTimeout(function(){ btn.innerHTML = old; btn.classList.remove('btn-success'); btn.classList.add('btn-outline-secondary'); }, 1500);
}
</script>

<?= $this->endSection() ?>
