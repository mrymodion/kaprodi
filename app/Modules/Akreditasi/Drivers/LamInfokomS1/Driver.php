<?php

namespace Modules\Akreditasi\Drivers\LamInfokomS1;

use Modules\Akreditasi\Drivers\LamInfokomD3\Driver as D3Driver;

/**
 * Driver Instrumen LAM INFOKOM 2.0 untuk Program SARJANA (S1).
 *
 * Mewarisi pembangunan tabel LKPS (buildLkpsTable) dari driver D3 karena
 * tabel data inti (EWMP, mahasiswa, masa tunggu, penelitian, PkM, publikasi,
 * HKI, kerjasama) identik antar jenjang. Tabel S1 yang belum punya sumber
 * data terstruktur (mis. 1.A.1, 1.A.2, 2.A.2) akan menghasilkan tabel kosong
 * dan diisi manual oleh tim GKM.
 *
 * Auto-skor (computeScores) dibiarkan kosong: struktur butir S1 berbeda dari
 * D3 sehingga skor diisi manual melalui halaman Penilaian. Formula auto-skor
 * khusus S1 dapat ditambahkan inkremental kemudian.
 */
class Driver extends D3Driver
{
    public function code(): string { return 'lam-infokom-2.0-s1'; }
    public function name(): string { return 'LAM INFOKOM 2.0 Sarjana'; }

    public function computeScores(int $prodiId, ?int $tahunAkademikId = null): array
    {
        return [];
    }
}
