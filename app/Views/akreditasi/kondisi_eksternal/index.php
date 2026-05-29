<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php
$KAT = \App\Models\KondisiEksternalModel::KATEGORI;
$DMP = \App\Models\KondisiEksternalModel::DAMPAK_LABELS;
$grup = [];
foreach ($KAT as $k => $info) $grup[$info['grup']][$k] = $info;
$active = $_GET['kategori'] ?? array_key_first($KAT);
?>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-globe2"></i> Kondisi Eksternal &mdash; <span class="badge bg-primary">A</span> Input Terstruktur</h4>
    <div class="d-flex gap-2">
        <a href="<?= site_url('akreditasi/kondisi-eksternal/narasi') ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-file-text"></i> Narasi LED</a>
        <a href="<?= site_url('akreditasi/kondisi-eksternal/mutu') ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-speedometer2"></i> Evaluasi Mutu</a>
        <a href="<?= site_url('eviden/audit/kondisi-eksternal') ?>" class="btn btn-sm btn-outline-info"><i class="bi bi-clipboard-data"></i> Audit Kesiapan</a>
    </div>
</div>

<?php if (session('success')): ?>
<div class="alert alert-success alert-dismissible fade show small py-2"><i class="bi bi-check-circle"></i> <?= esc(session('success')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>
<?php if (session('error')): ?>
<div class="alert alert-danger alert-dismissible fade show small py-2"><?= esc(session('error')) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif ?>

<div class="alert alert-light border small">
    <i class="bi bi-info-circle"></i> Catat faktor eksternal yang mempengaruhi prodi dalam <strong>10 kategori</strong> di bawah (kerangka PESTEL + analisis pasar + SWOT eksternal). Output dipakai untuk: (1) sumber narasi LED Bagian A, (2) bukti otomatis di PPEPP, (3) input ke analisis SWOT pengembangan prodi.
</div>

<ul class="nav nav-pills flex-wrap mb-3 small" role="tablist">
<?php foreach ($KAT as $key => $info): $n = $jumlah[$key] ?? 0; ?>
    <li class="nav-item">
        <a class="nav-link py-1 px-2 <?= $active === $key ? 'active' : '' ?>" data-bs-toggle="tab" href="#kat-<?= $key ?>">
            <i class="bi <?= $info['ikon'] ?>"></i> <?= esc($info['label']) ?>
            <?php if ($n > 0): ?><span class="badge bg-light text-dark ms-1"><?= $n ?></span><?php endif ?>
        </a>
    </li>
<?php endforeach ?>
</ul>

<div class="tab-content">
<?php foreach ($KAT as $key => $info): $entries = $grouped[$key] ?? []; ?>
    <div class="tab-pane fade <?= $active === $key ? 'show active' : '' ?>" id="kat-<?= $key ?>">
        <div class="card mb-3">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div>
                    <strong><i class="bi <?= $info['ikon'] ?>"></i> <?= esc($info['label']) ?></strong>
                    <span class="badge bg-secondary ms-1"><?= esc($info['grup']) ?></span>
                </div>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="openSaranAi('<?= $key ?>', '<?= esc($info['label'], 'js') ?>')"><i class="bi bi-robot"></i> Saran AI</button>
                    <a href="<?= site_url('akreditasi/kondisi-eksternal/baru') . '?kategori=' . $key ?>" class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> Tambah Entri</a>
                </div>
            </div>
            <div class="card-body py-2 small text-muted"><?= esc($info['desc']) ?></div>
            <?php if (empty($entries)): ?>
                <div class="card-body text-center text-muted py-4"><i class="bi bi-inbox"></i> Belum ada entri pada kategori ini.</div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Judul</th><th>Deskripsi</th><th class="text-center">Dampak</th><th>Respons Prodi</th><th>Sumber/Bukti</th><th class="text-end">Aksi</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($entries as $r): ?>
                        <tr>
                            <td style="width:18%"><strong><?= esc($r['judul']) ?></strong><?php if ($r['tahun_relevansi']): ?><small class="text-muted d-block"><?= $r['tahun_relevansi'] ?></small><?php endif ?></td>
                            <td><?= nl2br(esc(mb_strimwidth($r['deskripsi'], 0, 220, '…'))) ?></td>
                            <td class="text-center"><span class="badge bg-<?= $DMP[$r['dampak']]['color'] ?>"><?= $DMP[$r['dampak']]['label'] ?></span></td>
                            <td style="width:22%"><small><?= !empty($r['respons_prodi']) ? nl2br(esc(mb_strimwidth($r['respons_prodi'], 0, 180, '…'))) : '<span class="text-muted">—</span>' ?></small></td>
                            <td style="width:18%">
                                <?php if (!empty($r['sumber_referensi'])): ?><small class="d-block text-muted"><i class="bi bi-bookmark"></i> <?= esc($r['sumber_referensi']) ?></small><?php endif ?>
                                <?php if (!empty($r['link_bukti'])): ?><small><a href="<?= esc($r['link_bukti'], 'attr') ?>" target="_blank"><i class="bi bi-link-45deg"></i> Bukti</a></small><?php endif ?>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="<?= site_url("akreditasi/kondisi-eksternal/{$r['id']}/edit") ?>" class="btn btn-sm btn-outline-primary py-0 px-1"><i class="bi bi-pencil"></i></a>
                                <form method="post" action="<?= site_url("akreditasi/kondisi-eksternal/{$r['id']}/delete") ?>" class="d-inline" onsubmit="return confirm('Hapus entri ini?')">
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
        </div>
    </div>
<?php endforeach ?>
</div>

<div class="modal fade" id="modalSaranAi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-robot"></i> Saran AI &mdash; <span id="modalKatLabel"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modalLoading" class="text-center py-4">
                    <div class="spinner-border text-primary"></div>
                    <p class="mt-2 mb-0 small text-muted">AI sedang menyiapkan saran berdasarkan konteks prodi Anda... (5-15 detik)</p>
                </div>
                <div id="modalError" class="alert alert-danger small" style="display:none"></div>
                <form id="modalForm" method="post" action="<?= site_url('akreditasi/kondisi-eksternal/saran-ai-simpan') ?>" style="display:none">
                    <?= csrf_field() ?>
                    <input type="hidden" name="kategori" id="modalKatKey">
                    <div class="alert alert-warning small py-2"><i class="bi bi-exclamation-triangle"></i> <strong>Wajib diverifikasi:</strong> AI dapat menyarankan nomor peraturan/sumber yang perlu Anda periksa kebenarannya. Centang yang relevan saja, edit bila perlu, lalu simpan.</div>
                    <div id="modalSuggestions"></div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleAll()">Centang/Lepas Semua</button>
                        <button class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambahkan Entri Terpilih</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let modalSaranAi;
document.addEventListener('DOMContentLoaded', () => {
    modalSaranAi = new bootstrap.Modal(document.getElementById('modalSaranAi'));
});

async function openSaranAi(kat, label) {
    document.getElementById('modalKatLabel').textContent = label;
    document.getElementById('modalKatKey').value = kat;
    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalError').style.display = 'none';
    document.getElementById('modalForm').style.display = 'none';
    document.getElementById('modalSuggestions').innerHTML = '';
    modalSaranAi.show();

    try {
        const fd = new FormData();
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        const res = await fetch(`<?= site_url('akreditasi/kondisi-eksternal/saran-ai') ?>/${kat}`, { method: 'POST', body: fd });
        const data = await res.json();
        document.getElementById('modalLoading').style.display = 'none';
        if (!data.ok) {
            document.getElementById('modalError').textContent = data.error || 'Gagal mendapatkan saran AI.';
            document.getElementById('modalError').style.display = 'block';
            return;
        }
        if (!data.items || data.items.length === 0) {
            document.getElementById('modalError').textContent = 'AI tidak menghasilkan saran. Coba lagi.';
            document.getElementById('modalError').style.display = 'block';
            return;
        }
        renderSaranSuggestions(data.items);
        document.getElementById('modalForm').style.display = 'block';
    } catch (e) {
        document.getElementById('modalLoading').style.display = 'none';
        document.getElementById('modalError').textContent = 'Error: ' + e.message;
        document.getElementById('modalError').style.display = 'block';
    }
}

function renderSaranSuggestions(items) {
    const dmpColor = d => d === 'tinggi' ? 'danger' : (d === 'sedang' ? 'warning text-dark' : 'secondary');
    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]));
    const escAttr = s => esc(s);

    document.getElementById('modalSuggestions').innerHTML = items.map((s, i) => `
        <div class="card mb-2">
            <div class="card-body py-2">
                <div class="form-check mb-1">
                    <input class="form-check-input sel-cb" type="checkbox" name="items[${i}][selected]" value="1" id="sel-${i}" checked>
                    <label class="form-check-label fw-bold" for="sel-${i}">${esc(s.judul)}</label>
                    <span class="badge bg-${dmpColor(s.dampak)} ms-2">${esc(s.dampak)}</span>
                </div>
                <input type="hidden" name="items[${i}][judul]" value="${escAttr(s.judul)}">
                <input type="hidden" name="items[${i}][dampak]" value="${escAttr(s.dampak)}">
                <textarea name="items[${i}][deskripsi]" class="form-control form-control-sm mb-1" rows="2">${esc(s.deskripsi)}</textarea>
                <div class="row g-1">
                    <div class="col-md-6"><input type="text" name="items[${i}][respons_prodi]" class="form-control form-control-sm" placeholder="Respons prodi (opsional)" value="${escAttr(s.respons_prodi)}"></div>
                    <div class="col-md-6"><input type="text" name="items[${i}][sumber_referensi]" class="form-control form-control-sm" placeholder="Sumber (perlu verifikasi)" value="${escAttr(s.sumber_referensi)}"></div>
                </div>
            </div>
        </div>
    `).join('');
}

function toggleAll() {
    const cbs = document.querySelectorAll('.sel-cb');
    const allChecked = Array.from(cbs).every(c => c.checked);
    cbs.forEach(c => c.checked = !allChecked);
}
</script>

<?= $this->endSection() ?>
