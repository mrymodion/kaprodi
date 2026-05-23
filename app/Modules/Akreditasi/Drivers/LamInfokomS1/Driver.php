<?php

namespace Modules\Akreditasi\Drivers\LamInfokomS1;

use Modules\Akreditasi\Drivers\LamInfokomD3\Driver as D3Driver;

/**
 * Driver Instrumen LAM INFOKOM 2.0 untuk Program SARJANA (S1).
 *
 * Struktur butir, pembangunan tabel LKPS, dan logika auto-skor identik
 * dengan D3 (kode butir & pemetaan metrik sama: 2.2.A DTPR, 2.2.B kurikulum,
 * 2.2.D lulusan, 3.2.C penelitian, 4.2.C PkM), sehingga seluruhnya diwarisi
 * dari driver D3. Yang membedakan hanya bobot kriteria di seeder masing-masing.
 */
class Driver extends D3Driver
{
    public function code(): string { return 'lam-infokom-2.0-s1'; }
    public function name(): string { return 'LAM INFOKOM 2.0 Sarjana'; }
}
