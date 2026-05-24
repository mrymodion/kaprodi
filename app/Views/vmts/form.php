<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h4 class="mb-3"><i class="bi bi-bullseye"></i> Visi, Misi, Tujuan & Strategi (VMTS)</h4>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('errors')): ?>
<div class="alert alert-danger"><ul class="mb-0"><?php foreach ((array) session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
<?php endif ?>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-2">
            <button type="button" id="btnAiVmts" class="btn btn-sm btn-outline-info"><i class="bi bi-robot"></i> Bantu AI — Draf VMTS</button>
        </div>
        <form method="post" action="<?= site_url('vmts/save') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Visi <span class="text-danger">*</span></label>
                <textarea name="visi" class="form-control" rows="3" required><?= esc(old('visi', $vmts['visi'] ?? '')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Misi <span class="text-danger">*</span></label>
                <textarea name="misi" class="form-control" rows="4" required placeholder="Boleh ditulis poin per baris"><?= esc(old('misi', $vmts['misi'] ?? '')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Tujuan <span class="text-danger">*</span></label>
                <textarea name="tujuan" class="form-control" rows="4" required><?= esc(old('tujuan', $vmts['tujuan'] ?? '')) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Strategi</label>
                <textarea name="strategi" class="form-control" rows="3"><?= esc(old('strategi', $vmts['strategi'] ?? '')) ?></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. SK Penetapan</label>
                    <input type="text" name="no_sk" class="form-control" value="<?= esc(old('no_sk', $vmts['no_sk'] ?? '')) ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Tgl SK</label>
                    <input type="date" name="tgl_sk" class="form-control" value="<?= esc(old('tgl_sk', $vmts['tgl_sk'] ?? '')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Berlaku Mulai</label>
                    <input type="date" name="berlaku_mulai" class="form-control" value="<?= esc(old('berlaku_mulai', $vmts['berlaku_mulai'] ?? '')) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Berlaku Sampai</label>
                    <input type="date" name="berlaku_sampai" class="form-control" value="<?= esc(old('berlaku_sampai', $vmts['berlaku_sampai'] ?? '')) ?>">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan VMTS</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('btnAiVmts')?.addEventListener('click', function () {
    var b = this, orig = b.innerHTML;
    if (!confirm('Buat draf VMTS dengan AI? Isian saat ini akan ditimpa.')) return;
    b.disabled = true; b.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Membuat...';
    fetch('<?= site_url('ai/draft/vmts') ?>', {method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(r => r.json()).then(function (d) {
            if (d && d.ok && d.vmts) {
                document.querySelector('[name="visi"]').value = d.vmts.visi || '';
                document.querySelector('[name="misi"]').value = d.vmts.misi || '';
                document.querySelector('[name="tujuan"]').value = d.vmts.tujuan || '';
                document.querySelector('[name="strategi"]').value = d.vmts.strategi || '';
            } else { alert(d && d.error ? d.error : 'Gagal membuat draf.'); }
        }).catch(() => alert('Gagal menghubungi server.'))
          .finally(() => { b.disabled = false; b.innerHTML = orig; });
});
</script>

<?= $this->endSection() ?>
