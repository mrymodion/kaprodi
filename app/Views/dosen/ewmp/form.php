<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
$nama = trim(($dosen['gelar_depan'] ? $dosen['gelar_depan'].' ' : '').$dosen['nama'].($dosen['gelar_belakang'] ? ', '.$dosen['gelar_belakang'] : ''));
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-table"></i> Input EWMP — <?= esc($nama) ?></h4>
    <a href="<?= site_url('dosen/ewmp'.($taId ? '?ta_id='.$taId : '')) ?>" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header bg-primary text-white fw-semibold">Profil Dosen</div>
            <div class="card-body small">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted">NIDN</th><td><code><?= esc($dosen['nidn'] ?? '-') ?></code></td></tr>
                    <tr><th class="text-muted">Nama</th><td><?= esc($nama) ?></td></tr>
                    <tr><th class="text-muted">Jabatan</th><td><?= esc($dosen['jabatan_akademik'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Pendidikan</th><td><?= esc($dosen['pendidikan_terakhir'] ?? '-') ?></td></tr>
                    <tr><th class="text-muted">Linieritas</th><td><?= $dosen['linieritas'] === 'linier' ? '<span class="badge bg-success">Linier</span>' : '<span class="badge bg-warning text-dark">Tidak Linier</span>' ?></td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3 bg-light border-info">
            <div class="card-body small">
                <div class="fw-semibold mb-1 text-info"><i class="bi bi-info-circle"></i> Panduan EWMP</div>
                <table class="table table-sm table-borderless mb-0">
                    <tr><td>Pendidikan PS</td><td class="text-end">Maks. 10 SKS</td></tr>
                    <tr><td>PS Lain (PT ini)</td><td class="text-end">Maks. 6 SKS</td></tr>
                    <tr><td>PT Lain</td><td class="text-end">Maks. 6 SKS</td></tr>
                    <tr><td>Penelitian</td><td class="text-end">Min. 2 SKS/tahun</td></tr>
                    <tr><td>PkM</td><td class="text-end">Min. 1 SKS/tahun</td></tr>
                    <tr class="table-success fw-semibold"><td>Total Normal</td><td class="text-end">12–16 SKS</td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-success text-white fw-semibold">Form EWMP</div>
            <div class="card-body">
                <form method="post" action="<?= site_url("dosen/ewmp/{$dosen['id']}/store") ?>?ta_id=<?= $taId ?>">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tahun Akademik <span class="text-danger">*</span></label>
                        <select name="tahun_akademik_id" class="form-select" required onchange="updateTotal()">
                            <option value="">— Pilih —</option>
                            <?php foreach ($taList as $ta): ?>
                                <option value="<?= $ta['id'] ?>" <?= $taId == $ta['id'] ? 'selected' : '' ?>>
                                    <?= esc($ta['kode']) ?><?= $ta['is_ts'] ? ' (TS)' : '' ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <?php
                    $fields = [
                        'sks_pendidikan_ps'         => 'Pendidikan di PS Sendiri',
                        'sks_pendidikan_ps_lain_pt'  => 'Pendidikan di PS Lain (PT ini)',
                        'sks_pendidikan_pt_lain'     => 'Pendidikan di PT Lain',
                        'sks_penelitian'             => 'Penelitian',
                        'sks_pkm'                    => 'Pengabdian kepada Masyarakat (PkM)',
                        'sks_tugas_tambahan'         => 'Tugas Tambahan / Struktural',
                    ];
                    foreach ($fields as $name => $label):
                        $val = old($name, $ewmp[$name] ?? 0);
                    ?>
                    <div class="mb-3">
                        <label class="form-label"><?= $label ?> (SKS)</label>
                        <input type="number" name="<?= $name ?>" class="form-control sks-input"
                            min="0" max="20" step="0.5" value="<?= $val ?>" oninput="updateTotal()">
                    </div>
                    <?php endforeach ?>

                    <div class="card bg-primary text-white mb-3">
                        <div class="card-body py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total EWMP</span>
                            <span class="fs-4 fw-bold" id="totalEwmp">0</span>
                            <span>SKS</span>
                        </div>
                    </div>

                    <div id="ewmpStatus" class="alert d-none mb-3"></div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-floppy"></i> Simpan EWMP
                        </button>
                        <a href="<?= site_url('dosen/ewmp'.($taId ? '?ta_id='.$taId : '')) ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateTotal() {
    const inputs = document.querySelectorAll('.sks-input');
    let total = 0;
    inputs.forEach(i => { total += parseFloat(i.value || 0); });
    total = Math.round(total * 10) / 10;
    document.getElementById('totalEwmp').textContent = total;

    const status = document.getElementById('ewmpStatus');
    if (total === 0) {
        status.className = 'alert d-none mb-3';
    } else if (total >= 12 && total <= 16) {
        status.className = 'alert alert-success mb-3';
        status.innerHTML = '<i class="bi bi-check-circle-fill"></i> EWMP normal (12–16 SKS).';
    } else if (total < 12) {
        status.className = 'alert alert-warning mb-3';
        status.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> EWMP kurang dari 12 SKS — perlu perhatian.';
    } else {
        status.className = 'alert alert-danger mb-3';
        status.innerHTML = '<i class="bi bi-x-circle-fill"></i> EWMP melebihi 16 SKS — periksa kembali.';
    }
}
document.addEventListener('DOMContentLoaded', updateTotal);
</script>

<?= $this->endSection() ?>
