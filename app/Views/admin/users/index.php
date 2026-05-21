<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Manajemen User</h3>
    <a href="<?= site_url('admin/users/create') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah</a>
</div>
<?php if (session('success')): ?><div class="alert alert-success"><?= esc(session('success')) ?></div><?php endif ?>
<?php if (session('error')): ?><div class="alert alert-danger"><?= esc(session('error')) ?></div><?php endif ?>

<div class="card shadow-sm"><div class="card-body p-0">
<table class="table table-hover mb-0">
    <thead class="table-light">
        <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Prodi & Peran</th><th>Aksi</th></tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u->id ?></td>
            <td><?= esc($u->username) ?></td>
            <td><?= esc($u->email) ?></td>
            <td><span class="badge bg-info"><?= esc($u->groups_str) ?></span></td>
            <td class="small"><?= esc($u->prodi_str ?: '-') ?></td>
            <td>
                <a class="btn btn-sm btn-outline-primary" href="<?= site_url('admin/users/'.$u->id.'/edit') ?>"><i class="bi bi-pencil"></i></a>
                <form action="<?= site_url('admin/users/'.$u->id.'/delete') ?>" method="post" class="d-inline" onsubmit="return confirm('Hapus user?')">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
</div></div>
<?= $this->endSection() ?>
