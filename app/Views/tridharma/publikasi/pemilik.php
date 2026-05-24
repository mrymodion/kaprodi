<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $ownerSet = array_flip(array_map('intval', $owners)); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-people"></i> Atur Dosen Pemilik</h4>
    <a href="<?= site_url('publikasi') ?>#tab-pub" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="fw-semibold"><?= esc($item['judul']) ?></div>
        <div class="small text-muted">
            <?= esc($item['nama_media'] ?? '') ?><?= $item['tahun'] ? ' (' . $item['tahun'] . ')' : '' ?>
            <?php if ($item['doi']): ?> &middot; DOI <?= esc($item['doi']) ?><?php endif ?>
        </div>
    </div>
</div>

<form method="post" action="<?= site_url("publikasi/{$item['id']}/pemilik") ?>">
    <?= csrf_field() ?>
    <div class="card">
        <div class="card-header bg-white fw-semibold">Pilih dosen penulis (boleh lebih dari satu untuk karya bersama)</div>
        <div class="card-body">
            <?php if (empty($dosenList)): ?>
                <p class="text-muted mb-0">Belum ada dosen pada prodi ini.</p>
            <?php else: ?>
                <div class="row g-2">
                <?php foreach ($dosenList as $d): ?>
                    <?php $nm = trim(($d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '') . $d['nama'] . ($d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '')); ?>
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="dosen_ids[]" value="<?= (int) $d['id'] ?>" id="d<?= (int) $d['id'] ?>" <?= isset($ownerSet[(int) $d['id']]) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="d<?= (int) $d['id'] ?>">
                                <?= esc($nm) ?><?php if (!empty($d['nidn'])): ?> <small class="text-muted">(<?= esc($d['nidn']) ?>)</small><?php endif ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary btn-sm"><i class="bi bi-save"></i> Simpan Pemilik</button>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
