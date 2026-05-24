<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-table"></i> EWMP Saya</h4>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-info small"><i class="bi bi-info-circle"></i> Ekuivalensi Waktu Mengajar Penuh (EWMP) per semester. Total normal <strong>12–16 SKS</strong>.</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Input / Perbarui EWMP</div>
            <div class="card-body">
                <form method="post" action="<?= site_url('akun/ewmp') ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                        <select name="tahun_akademik_id" class="form-select" required onchange="location.href='<?= site_url('akun/ewmp') ?>?ta_id='+this.value">
                            <option value="">— Pilih —</option>
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>><?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php
                    $fields = [
                        'sks_pendidikan_ps'         => 'Pendidikan di PS Sendiri',
                        'sks_pendidikan_ps_lain_pt' => 'Pendidikan di PS Lain (PT ini)',
                        'sks_pendidikan_pt_lain'    => 'Pendidikan di PT Lain',
                        'sks_penelitian'            => 'Penelitian',
                        'sks_pkm'                   => 'Pengabdian kepada Masyarakat (PkM)',
                        'sks_tugas_tambahan'        => 'Tugas Tambahan / Struktural',
                    ];
                    foreach ($fields as $name => $label): $val = old($name, $ewmp[$name] ?? 0); ?>
                    <div class="mb-2">
                        <label class="form-label small mb-0"><?= $label ?> (SKS)</label>
                        <input type="number" name="<?= $name ?>" class="form-control form-control-sm sks-input" min="0" max="20" step="0.5" value="<?= $val ?>" oninput="updateTotal()">
                    </div>
                    <?php endforeach ?>
                    <div class="card bg-primary text-white my-3">
                        <div class="card-body py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total EWMP</span>
                            <span class="fs-4 fw-bold" id="totalEwmp">0</span><span>SKS</span>
                        </div>
                    </div>
                    <div id="ewmpStatus" class="alert d-none py-2 mb-3"></div>
                    <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Simpan EWMP</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white fw-semibold">Riwayat EWMP</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th>TA</th><th class="text-center">Pendidikan</th><th class="text-center">Penelitian</th><th class="text-center">PkM</th><th class="text-center">Total</th></tr></thead>
                        <tbody>
                        <?php if (empty($riwayat)): ?>
                            <tr><td colspan="5" class="text-center text-muted py-4">Belum ada data EWMP.</td></tr>
                        <?php else: foreach ($riwayat as $e): $pend = $e['sks_pendidikan_ps'] + $e['sks_pendidikan_ps_lain_pt'] + $e['sks_pendidikan_pt_lain']; ?>
                            <tr>
                                <td><?= esc($e['ta_kode']) ?><?= $e['is_ts'] ? ' <span class="badge bg-info">TS</span>' : '' ?></td>
                                <td class="text-center"><?= $pend ?></td>
                                <td class="text-center"><?= $e['sks_penelitian'] ?></td>
                                <td class="text-center"><?= $e['sks_pkm'] ?></td>
                                <td class="text-center"><span class="badge <?= $e['total_sks'] >= 12 && $e['total_sks'] <= 16 ? 'bg-success' : 'bg-warning text-dark' ?>"><?= $e['total_sks'] ?></span></td>
                            </tr>
                        <?php endforeach; endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    let total = 0;
    document.querySelectorAll('.sks-input').forEach(i => total += parseFloat(i.value || 0));
    total = Math.round(total * 10) / 10;
    document.getElementById('totalEwmp').textContent = total;
    const s = document.getElementById('ewmpStatus');
    if (total === 0) { s.className = 'alert d-none py-2 mb-3'; }
    else if (total >= 12 && total <= 16) { s.className = 'alert alert-success py-2 mb-3'; s.innerHTML = '<i class="bi bi-check-circle-fill"></i> EWMP normal (12–16 SKS).'; }
    else if (total < 12) { s.className = 'alert alert-warning py-2 mb-3'; s.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> EWMP kurang dari 12 SKS.'; }
    else { s.className = 'alert alert-danger py-2 mb-3'; s.innerHTML = '<i class="bi bi-x-circle-fill"></i> EWMP melebihi 16 SKS.'; }
}
document.addEventListener('DOMContentLoaded', updateTotal);
</script>

<?= $this->endSection() ?>
