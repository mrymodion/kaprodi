<?php

namespace App\Services;

use Config\Database;
use Modules\Akreditasi\Engine\AkreditasiEngine;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Generator file Excel LKPS (Laporan Kinerja Program Studi).
 *
 * Mengambil definisi tabel dari `lam_tabel_lkps` dan data dari driver
 * akreditasi prodi (AccreditationDriver::buildLkpsTable).
 *
 * Output: satu workbook .xlsx dengan banyak sheet, satu per tabel LKPS.
 */
class LkpsGenerator
{
    private \CodeIgniter\Database\BaseConnection $db;
    private AkreditasiEngine $engine;

    /** Skema kolom default per kode tabel (judul kolom yang ditampilkan di sheet). */
    private const COLUMN_SCHEMAS = [
        '1.A.4' => [
            'No', 'NIDN', 'Nama Dosen',
            'SKS Pendidikan PS', 'SKS PS Lain PT', 'SKS PT Lain',
            'SKS Penelitian', 'SKS PkM', 'SKS Tugas Tambahan', 'Total SKS',
        ],
        '2.A.1' => ['Jenis Kelamin', 'Status', 'Jumlah'],
        '2.B.4' => ['No', 'NIM', 'Nama Lulusan', 'Masa Tunggu (bulan)', 'Kesesuaian Bidang Kerja', 'Nama Perusahaan', 'Jabatan'],
        '2.B.5' => ['No', 'NIM', 'Nama Lulusan', 'Tahun Lulus', 'Kesesuaian Bidang Kerja', 'Tingkat Pekerjaan', 'Nama Perusahaan', 'Jabatan'],
        '2.B.6' => ['No', 'Aspek Kemampuan', 'Sangat Baik (%)', 'Baik (%)', 'Cukup (%)', 'Kurang (%)', 'Jml Responden'],
        '3.A.2' => ['No', 'Judul Penelitian', 'Ketua', 'Sumber Dana', 'Nama Sumber', 'Jumlah Dana (Rp)', 'Tahun', 'Libatkan Mahasiswa', 'Jml Mhs'],
        '3.C.1' => ['No', 'Nama Mitra', 'Tingkat', 'Jenis Dokumen', 'No. Dokumen', 'Tgl Mulai', 'Tgl Berakhir', 'Manfaat', 'Bukti Implementasi'],
        '3.C.2' => ['No', 'Jenis', 'Judul', 'Penulis', 'Nama Media', 'Tahun', 'Volume', 'DOI', 'Sitasi', 'SINTA'],
        '3.C.3' => ['No', 'Jenis HKI', 'Judul', 'No. HKI', 'Tahun', 'Pemilik', 'Status'],
        '4.A.2' => ['No', 'Judul PkM', 'Ketua', 'Sumber Dana', 'Nama Sumber', 'Jumlah Dana (Rp)', 'Mitra', 'Lokasi', 'Tahun', 'Libatkan Mahasiswa', 'Jml Mhs'],
        '4.C.1' => ['No', 'Nama Mitra', 'Tingkat', 'Jenis Dokumen', 'No. Dokumen', 'Tgl Mulai', 'Tgl Berakhir', 'Manfaat', 'Bukti Implementasi'],
        '4.C.2' => ['No', 'Jenis', 'Judul', 'Penulis', 'Nama Media', 'Tahun', 'Volume', 'DOI', 'Sitasi', 'SINTA'],
        '4.C.3' => ['No', 'Jenis HKI', 'Judul', 'No. HKI', 'Tahun', 'Pemilik', 'Status'],
    ];

    public function __construct()
    {
        $this->db     = Database::connect();
        $this->engine = new AkreditasiEngine();
    }

    /** Daftar semua tabel LKPS untuk instrumen prodi. */
    public function listTabel(int $prodiId): array
    {
        $instrumen = $this->engine->getInstrumen($prodiId);
        if (!$instrumen) return [];

        return $this->db->query("
            SELECT * FROM lam_tabel_lkps WHERE lam_instrumen_id = ?
            ORDER BY urutan
        ", [$instrumen['id']])->getResultArray();
    }

    /** Generate seluruh workbook LKPS untuk prodi, return path file. */
    public function generateAll(int $prodiId, ?int $taId = null): string
    {
        $instrumen = $this->engine->getInstrumen($prodiId);
        if (!$instrumen) {
            throw new \RuntimeException('Prodi belum memiliki instrumen LAM aktif.');
        }
        $driver = $this->engine->getDriver($prodiId);
        if (!$driver) {
            throw new \RuntimeException('Driver akreditasi tidak ditemukan.');
        }

        $prodi = $this->db->table('prodi')->where('id', $prodiId)->get()->getRowArray();
        $tabelList = $this->listTabel($prodiId);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator(env('app.namaAplikasi', 'KAPRODI'))
            ->setTitle('LKPS — ' . ($prodi['nama'] ?? ''))
            ->setSubject($instrumen['nama']);

        // Sheet pertama: Cover
        $this->buildCoverSheet($spreadsheet->getActiveSheet(), $prodi, $instrumen);

        // Satu sheet per tabel LKPS
        foreach ($tabelList as $t) {
            $rows = $driver->buildLkpsTable($t['kode'], $prodiId, $taId);
            $sheet = $spreadsheet->createSheet();
            $this->buildTabelSheet($sheet, $t, $rows);
        }

        $spreadsheet->setActiveSheetIndex(0);

        $filename = sprintf(
            'LKPS_%s_%s_%s.xlsx',
            preg_replace('/[^a-zA-Z0-9]/', '_', $prodi['kode'] ?? $prodi['nama']),
            date('Ymd_His'),
            $taId ? "TA{$taId}" : 'all'
        );
        $path = WRITEPATH . 'lkps/' . $filename;

        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }

    /** Generate satu sheet untuk tabel tertentu, return path. */
    public function generateSingle(int $prodiId, string $kodeTabel, ?int $taId = null): string
    {
        $driver = $this->engine->getDriver($prodiId);
        if (!$driver) {
            throw new \RuntimeException('Driver akreditasi tidak ditemukan.');
        }

        $prodi = $this->db->table('prodi')->where('id', $prodiId)->get()->getRowArray();
        $tabelMeta = $this->db->table('lam_tabel_lkps')
            ->where('kode', $kodeTabel)->get()->getRowArray();
        if (!$tabelMeta) {
            throw new \RuntimeException("Tabel LKPS '{$kodeTabel}' tidak terdaftar.");
        }

        $rows = $driver->buildLkpsTable($kodeTabel, $prodiId, $taId);

        $spreadsheet = new Spreadsheet();
        $this->buildTabelSheet($spreadsheet->getActiveSheet(), $tabelMeta, $rows);

        $filename = sprintf(
            'LKPS_%s_%s_%s.xlsx',
            preg_replace('/[^a-zA-Z0-9]/', '_', $kodeTabel),
            preg_replace('/[^a-zA-Z0-9]/', '_', $prodi['kode'] ?? $prodi['nama']),
            date('Ymd_His')
        );
        $path = WRITEPATH . 'lkps/' . $filename;

        (new Xlsx($spreadsheet))->save($path);
        return $path;
    }

    // ── helpers ───────────────────────────────────────────────────────────

    private function buildCoverSheet(Worksheet $sheet, array $prodi, array $instrumen): void
    {
        $sheet->setTitle('Cover');
        $sheet->setCellValue('A1', 'LAPORAN KINERJA PROGRAM STUDI (LKPS)');
        $sheet->mergeCells('A1:F1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $info = [
            ['Program Studi',  $prodi['nama'] ?? '-'],
            ['Kode Prodi',     $prodi['kode'] ?? '-'],
            ['Jenjang',        $prodi['jenjang'] ?? '-'],
            ['Fakultas/UPPS',  $prodi['fakultas'] ?? $prodi['upps'] ?? '-'],
            ['Instrumen',      $instrumen['nama']],
            ['Tahun Terbit',   $instrumen['tahun_terbit'] ?? '-'],
            ['Tanggal Generate', date('d-m-Y H:i:s')],
            ['Aplikasi',       env('app.namaAplikasi', 'KAPRODI')],
        ];
        $row = 3;
        foreach ($info as [$k, $v]) {
            $sheet->setCellValue("A{$row}", $k);
            $sheet->setCellValue("B{$row}", $v);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(60);
    }

    private function buildTabelSheet(Worksheet $sheet, array $tabel, array $rows): void
    {
        // Title sheet name (max 31 chars)
        $sheet->setTitle(substr($tabel['kode'], 0, 31));

        // Header tabel
        $sheet->setCellValue('A1', "Tabel {$tabel['kode']} — {$tabel['judul']}");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);

        // Kolom: dari schema_json kalau ada, kalau tidak dari fallback,
        // kalau tidak juga, ambil dari key row pertama.
        $headers = $this->resolveHeaders($tabel, $rows);
        if (empty($headers)) {
            $sheet->setCellValue('A3', '(tidak ada data)');
            return;
        }

        $startRow = 3;
        $col = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue("{$col}{$startRow}", $h);
            $col++;
        }

        // Header style
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $headerRange = "A{$startRow}:{$lastCol}{$startRow}";
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D3B8C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);

        // Data rows
        $dataRow = $startRow + 1;
        $no = 1;
        $hasNoCol = ($headers[0] ?? '') === 'No';
        foreach ($rows as $r) {
            $col = 'A';
            if ($hasNoCol) {
                $sheet->setCellValue("{$col}{$dataRow}", $no++);
                $col++;
            }
            foreach ($this->mapRow($r, $tabel['kode']) as $val) {
                $sheet->setCellValue("{$col}{$dataRow}", is_scalar($val) ? $val : json_encode($val));
                $col++;
            }
            $dataRow++;
        }

        // Border + autosize
        if ($dataRow > $startRow + 1) {
            $allRange = "A{$startRow}:{$lastCol}" . ($dataRow - 1);
            $sheet->getStyle($allRange)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
                ],
            ]);
        }
        for ($c = 'A'; $c <= $lastCol; $c++) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }
        $sheet->getRowDimension($startRow)->setRowHeight(30);
    }

    /** Tentukan header kolom untuk tabel. */
    private function resolveHeaders(array $tabel, array $rows): array
    {
        // Prioritas 1: schema_json di lam_tabel_lkps
        if (!empty($tabel['schema_json'])) {
            $decoded = json_decode($tabel['schema_json'], true);
            if (is_array($decoded) && isset($decoded['columns']) && is_array($decoded['columns'])) {
                return $decoded['columns'];
            }
        }
        // Prioritas 2: skema default di kelas ini
        if (isset(self::COLUMN_SCHEMAS[$tabel['kode']])) {
            return self::COLUMN_SCHEMAS[$tabel['kode']];
        }
        // Prioritas 3: ambil key dari row pertama
        if (!empty($rows)) {
            return array_keys($rows[0]);
        }
        return [];
    }

    /** Map nilai row sesuai urutan kolom yang diinginkan di sheet. */
    private function mapRow(array $r, string $kodeTabel): array
    {
        // Mapping kustom per kode tabel
        return match ($kodeTabel) {
            '1.A.4' => [$r['nidn'] ?? '-', $r['nama'] ?? '-',
                $r['sks_pendidikan_ps'] ?? 0, $r['sks_pendidikan_ps_lain_pt'] ?? 0, $r['sks_pendidikan_pt_lain'] ?? 0,
                $r['sks_penelitian'] ?? 0, $r['sks_pkm'] ?? 0, $r['sks_tugas_tambahan'] ?? 0, $r['total_sks'] ?? 0],
            '2.A.1' => [$r['jenis_kelamin'] ?? '-', $r['status'] ?? '-', $r['jumlah'] ?? 0],
            '2.B.4' => [$r['nim'] ?? '-', $r['nama'] ?? '-',
                $r['masa_tunggu_bulan'] ?? '-', $r['kesesuaian_bidang_kerja'] ?? '-',
                $r['nama_perusahaan'] ?? '-', $r['jabatan'] ?? '-'],
            '2.B.5' => [$r['nim'] ?? '-', $r['nama'] ?? '-', $r['tgl_lulus'] ?? '-',
                str_replace('_', ' ', ucfirst($r['kesesuaian_bidang_kerja'] ?? '-')),
                ucfirst($r['tingkat_pekerjaan'] ?? '-'), $r['nama_perusahaan'] ?? '-', $r['jabatan'] ?? '-'],
            '2.B.6' => [$r['aspek'] ?? '-', $r['sangat_baik'] ?? 0, $r['baik'] ?? 0,
                $r['cukup'] ?? 0, $r['kurang'] ?? 0, $r['responden'] ?? 0],
            '3.A.2' => [$r['judul'] ?? '-', $r['ketua'] ?? '-',
                $r['sumber_dana'] ?? '-', $r['nama_sumber_dana'] ?? '-',
                $r['jumlah_dana'] ?? 0, $r['tahun_pelaksanaan'] ?? '-',
                ($r['libatkan_mahasiswa'] ?? 0) ? 'Ya' : 'Tidak',
                $r['jumlah_mhs_terlibat'] ?? 0],
            '4.A.2' => [$r['judul'] ?? '-', $r['ketua'] ?? '-',
                $r['sumber_dana'] ?? '-', $r['nama_sumber_dana'] ?? '-',
                $r['jumlah_dana'] ?? 0, $r['mitra'] ?? '-', $r['lokasi'] ?? '-',
                $r['tahun_pelaksanaan'] ?? '-',
                ($r['libatkan_mahasiswa'] ?? 0) ? 'Ya' : 'Tidak',
                $r['jumlah_mhs_terlibat'] ?? 0],
            '3.C.1', '4.C.1' => [$r['nama_mitra'] ?? '-', $r['tingkat'] ?? '-', $r['jenis_dokumen'] ?? '-',
                $r['no_dokumen'] ?? '-', $r['tgl_mulai'] ?? '-', $r['tgl_berakhir'] ?? '-',
                $r['manfaat'] ?? '-', $r['bukti_implementasi'] ?? '-'],
            '3.C.2', '4.C.2' => [$r['jenis'] ?? '-', $r['judul'] ?? '-', $r['penulis'] ?? '-',
                $r['nama_media'] ?? '-', $r['tahun'] ?? '-', $r['volume'] ?? '-',
                $r['doi'] ?? '-', $r['sitasi'] ?? 0, $r['sinta_score'] ?? '-'],
            '3.C.3', '4.C.3' => [$r['jenis'] ?? '-', $r['judul'] ?? '-', $r['no_hki'] ?? '-',
                $r['tahun'] ?? '-', $r['pemilik'] ?? '-', $r['status'] ?? '-'],
            default => array_values($r),
        };
    }
}
