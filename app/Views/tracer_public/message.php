<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Tracer Study</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .message-card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); max-width: 500px; width: 100%; text-align: center; padding: 40px 30px; }
        .icon-circle { width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px; }
        .icon-success { background-color: #d1e7dd; color: #0f5132; }
        .icon-error { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card message-card">
                    
                    <?php if (isset($success)): ?>
                        <div class="icon-circle icon-success">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-success">Berhasil</h3>
                        <p class="text-muted mb-0 fs-5"><?= esc($success) ?></p>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="icon-circle icon-error">
                            <i class="bi bi-x-lg"></i>
                        </div>
                        <h3 class="fw-bold mb-3 text-danger">Terjadi Kesalahan</h3>
                        <p class="text-muted mb-0 fs-5"><?= esc($error) ?></p>
                    <?php endif; ?>

                </div>
                
                <div class="text-center text-muted small mt-4">
                    &copy; <?= date('Y') ?> &middot; Program Studi
                </div>
            </div>
        </div>
    </div>

</body>
</html>