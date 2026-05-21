<!DOCTYPE html>
<html><head><meta charset="UTF-8"><title>Install Shield</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light p-5">
<div class="container">
    <div class="alert alert-warning">
        <h4><i class="bi bi-exclamation-triangle"></i> Shield Belum Terinstal</h4>
        <p>Jalankan perintah berikut di terminal:</p>
<pre class="bg-dark text-white p-3 rounded">composer require codeigniter4/shield
php spark shield:setup
php spark migrate
php spark db:seed AuthDemoSeeder</pre>
        <p>Setelah selesai, refresh halaman ini.</p>
    </div>
</div>
</body></html>
