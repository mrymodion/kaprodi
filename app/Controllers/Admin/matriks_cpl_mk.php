<?= $this->extend('layouts/main') ?>
<?= $this->section('title') ?>Matriks CPL - Mata Kuliah<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    /* UX Enhancement: Sticky Header & Hover Effects untuk Matriks Besar */
    .table-matrix th { position: sticky; top: 0; background-color: #0d3b8c; color: white; z-index: 10; text-align: center; vertical-align: middle; }
    .table-matrix th:first-child, .table-matrix td:first-child { position: sticky; left: 0; background-color: #f8f9fa; z-index: 11; font-weight: bold; border-right: 2px solid #dee2e6; }
    .table-matrix th:first-child { z-index: 12; background-color: #0d3b8c; }
    .table-matrix tbody tr:hover td { background-color: #e9ecef; }
    .matrix-checkbox { transform: scale(1.4); cursor: pointer; }
    .spinner-overlay { display: none; position: fixed; top: 20px; right: 20px; z-index: 9999; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0 text-dark"><i class="bi bi-grid-3x3 text-primary me-2"></i> Matriks Pemetaan CPL ke Mata Kuliah</h1>
            <p class="text-muted small mb-0 mt-1">Pemenuhan LKPS LAM Infokom Kriteria 2.B.3 (Relevansi Pendidikan)</p>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm w-auto shadow-sm" id="filterSemester" onchange="filterSemester(this.value)">
                <option value="all">Semua Semester</option>
                <?php for($i=1; $i<=8; $i++): ?>
                    <option value="<?= $i ?>">Semester <?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <div class="alert alert-info border-0 shadow-sm small py-2">
        <i class="bi bi-info-circle-fill me-1"></i> <strong>Pro-Tip:</strong> Perubahan akan disimpan secara otomatis. Klik pada kotak untuk memetakan Mata Kuliah terhadap CPL.
    </div>

    <!-- Indikator Loading Auto-save -->
    <div class="spinner-overlay" id="saveSpinner">
        <div class="toast show align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"><i class="bi bi-check-circle me-2"></i> Pemetaan disimpan!</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive" style="max-height: 65vh; overflow-y: auto;">
                <table class="table table-bordered table-matrix mb-0">
                    <thead>
                        <tr>
                            <th style="min-width: 250px;">Mata Kuliah \ CPL</th>
                            <?php foreach($cplList as $cpl): ?>
                                <th title="<?= esc($cpl['deskripsi']) ?>" style="width: 60px;">
                                    <div class="small fw-bold"><?= esc($cpl['kode']) ?></div>
                                </th>
                            <?php endforeach; ?>
                            <th style="min-width: 80px;">Pemenuhan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $currentSemester = 0;
                        foreach($mkList as $mk): 
                            // Visual Grouping per Semester
                            if($mk['semester'] != $currentSemester):
                                $currentSemester = $mk['semester'];
                        ?>
                            <tr class="semester-row" data-semester="<?= $currentSemester ?>" style="background-color: #e9ecef;">
                                <td colspan="<?= count($cplList) + 2 ?>" class="fw-bold text-primary py-1">
                                    <i class="bi bi-bookmark-fill me-1"></i> Semester <?= $currentSemester ?>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr class="mk-row" data-semester="<?= $mk['semester'] ?>" id="row-<?= $mk['id'] ?>">
                            <td>
                                <div class="fw-bold text-dark"><?= esc($mk['kode']) ?> - <?= esc($mk['nama']) ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= $mk['sks'] ?> SKS &middot; <?= esc($mk['rumpun_mk']) ?></div>
                            </td>
                            
                            <?php 
                            $checkCount = 0;
                            foreach($cplList as $cpl): 
                                $isChecked = in_array($cpl['id'], $petaMatrix[$mk['id']] ?? []);
                                if($isChecked) $checkCount++;
                            ?>
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input matrix-checkbox" 
                                           data-mk="<?= $mk['id'] ?>" data-cpl="<?= $cpl['id'] ?>"
                                           <?= $isChecked ? 'checked' : '' ?>
                                           onchange="togglePeta(this)">
                                </td>
                            <?php endforeach; ?>

                            <td class="text-center fw-bold align-middle">
                                <span class="badge rounded-pill bg-<?= $checkCount > 0 ? 'success' : 'danger' ?>" id="count-<?= $mk['id'] ?>">
                                    <?= $checkCount ?> CPL
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterSemester(sem) {
    document.querySelectorAll('.semester-row, .mk-row').forEach(row => {
        if (sem === 'all') row.style.display = '';
        else row.style.display = row.getAttribute('data-semester') === sem ? '' : 'none';
    });
}

async function togglePeta(checkbox) {
    const mkId = checkbox.getAttribute('data-mk');
    const cplId = checkbox.getAttribute('data-cpl');
    const isChecked = checkbox.checked;

    // Simulasi Fetch API ke Controller untuk menyimpan data
    // let formData = new FormData();
    // formData.append('mata_kuliah_id', mkId);
    // formData.append('cpl_id', cplId);
    // formData.append('status', isChecked ? 1 : 0);
    // formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    // await fetch('<?= site_url("kurikulum/toggle-peta") ?>', { method: 'POST', body: formData });

    // Update counter visual
    let row = document.getElementById('row-' + mkId);
    let countSpan = document.getElementById('count-' + mkId);
    let currentCount = parseInt(countSpan.innerText);
    currentCount = isChecked ? currentCount + 1 : currentCount - 1;
    countSpan.innerText = currentCount + ' CPL';
    countSpan.className = 'badge rounded-pill bg-' + (currentCount > 0 ? 'success' : 'danger');
}
</script>
<?= $this->endSection() ?>