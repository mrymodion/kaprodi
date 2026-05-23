<?php
/** Partial dokumen eviden. Var: $ref (dosen|tendik), $ownerId, $dokumen[], $jenisOpt[] */
?>
<div class="card mt-4">
    <div class="card-header bg-white fw-semibold"><i class="bi bi-folder2-open"></i> Dokumen Eviden (PDF)</div>
    <div class="card-body">
        <?php if (session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show py-2 small"><?= esc(session('success')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif ?>
        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show py-2 small"><?= esc(session('error')) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif ?>

        <?php if (empty($dokumen)): ?>
            <p class="text-muted small mb-3">Belum ada dokumen diunggah.</p>
        <?php else: ?>
        <div class="table-responsive mb-3">
            <table class="table table-sm align-middle mb-0">
                <tbody>
                <?php foreach ($dokumen as $d): ?>
                    <tr>
                        <td style="width:38px"><i class="bi bi-file-earmark-pdf text-danger"></i></td>
                        <td><?= esc($jenisOpt[$d['jenis']] ?? $d['jenis']) ?>
                            <div class="small text-muted"><?= esc($d['nama_asli'] ?? '-') ?></div></td>
                        <td class="text-end text-nowrap">
                            <a href="<?= site_url('dokumen/' . $d['id'] . '/lihat') ?>" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-eye"></i> Lihat</a>
                            <form method="post" action="<?= site_url('dokumen/' . $d['id'] . '/delete') ?>" class="d-inline" onsubmit="return confirm('Hapus dokumen ini?')">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger py-0 px-1"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>

        <form method="post" action="<?= site_url("dokumen/{$ref}/{$ownerId}/upload") ?>" enctype="multipart/form-data" class="row g-2 align-items-end">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label class="form-label small fw-semibold mb-1">Jenis Dokumen</label>
                <select name="jenis" class="form-select form-select-sm">
                    <?php foreach ($jenisOpt as $v => $l): ?><option value="<?= $v ?>"><?= esc($l) ?></option><?php endforeach ?>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small fw-semibold mb-1">Berkas PDF</label>
                <input type="file" name="dokumen_pdf" class="form-control form-control-sm" accept="application/pdf" required>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-upload"></i> Unggah</button>
            </div>
        </form>
        <small class="text-muted">PDF maks <?= (int) env('storage.maxUploadMb', 20) ?> MB, otomatis dikompres bila Ghostscript terpasang.</small>
    </div>
</div>
