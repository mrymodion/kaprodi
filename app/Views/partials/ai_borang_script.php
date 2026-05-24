<script>
document.querySelectorAll('.btn-ai-borang').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var form = btn.closest('form');
        var ta = form.querySelector('[name="' + btn.dataset.target + '"]');
        var body = new URLSearchParams();
        try {
            var ctx = JSON.parse(btn.dataset.ctx || '{}');
            Object.keys(ctx).forEach(function (p) {
                var el = form.querySelector('[name="' + ctx[p] + '"]');
                body.append(p, el ? el.value : '');
            });
        } catch (e) {}
        var orig = btn.innerHTML; btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        fetch('<?= site_url('ai/draft/') ?>' + btn.dataset.jenis, {method: 'POST', headers: {'X-Requested-With': 'XMLHttpRequest'}, body: body})
            .then(function (r) { return r.json(); }).then(function (d) {
                if (d && d.ok && ta) { ta.value = d.text; } else { alert(d && d.error ? d.error : 'Gagal membuat draf.'); }
            }).catch(function () { alert('Gagal menghubungi server.'); })
              .finally(function () { btn.disabled = false; btn.innerHTML = orig; });
    });
});
</script>
