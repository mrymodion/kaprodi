<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#f4f6f9}</style>
</head>
<body>
<div class="container py-5" style="max-width:560px">
    <div class="card shadow-sm">
        <div class="card-body text-center p-5">
            <?php if (!empty($success ?? '')): ?>
                <div class="display-5 text-success mb-3">&#10003;</div>
                <h5 class="mb-2">Berhasil</h5>
                <p class="text-muted mb-0"><?= $success // sudah di-esc di controller ?></p>
            <?php else: ?>
                <div class="display-5 text-danger mb-3">&#10007;</div>
                <h5 class="mb-2">Tidak Dapat Diproses</h5>
                <p class="text-muted mb-0"><?= esc($error ?? 'Terjadi kesalahan.') ?></p>
            <?php endif ?>
        </div>
    </div>
</div>
</body>
</html>
