<?php

namespace App\Controllers\Concerns;

use PhpOffice\PhpSpreadsheet\Shared\Date as XlsDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

/**
 * Helper umum untuk fitur import Excel (normalisasi header, parsing tanggal,
 * pembacaan file upload, dan pembuatan file template).
 */
trait ExcelImport
{
    /** Normalisasi nama header kolom Excel ke kunci kanonik (dengan alias opsional). */
    protected function normHeader(string $v, array $alias = []): string
    {
        $h = strtolower(trim($v));
        $h = preg_replace('/\(.*?\)/', '', $h);
        $h = preg_replace('/[^a-z0-9]+/', '_', $h);
        $h = trim((string) $h, '_');
        return $alias[$h] ?? $h;
    }

    /** Baca sel tanggal (serial Excel maupun teks) menjadi 'Y-m-d' atau null. */
    protected function parseDateCell(Worksheet $sheet, ?int $col, int $row): ?string
    {
        if (!$col) return null;
        $cell = $sheet->getCell([$col, $row]);
        $raw  = $cell->getValue();
        if ($raw === null || $raw === '') return null;
        if (XlsDate::isDateTime($cell) && is_numeric($raw)) {
            return XlsDate::excelToDateTimeObject((float) $raw)->format('Y-m-d');
        }
        $ts = strtotime((string) $raw);
        return $ts ? date('Y-m-d', $ts) : null;
    }

    /**
     * Baca file Excel yang diunggah pada field "file".
     * Return [Worksheet $sheet, array $colOf, int $highestRow] atau string pesan error.
     */
    protected function readUpload(array $alias = [])
    {
        $file = $this->request->getFile('file');
        if (!$file || !$file->isValid()) return 'File tidak valid. Unggah file Excel (.xlsx).';
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if (!in_array($ext, ['xlsx', 'xls'], true)) return 'Format harus .xlsx atau .xls.';

        // Verifikasi magic bytes agar file tidak sekadar di-rename ke .xlsx/.xls.
        $fh   = @fopen($file->getTempName(), 'rb');
        $head = $fh ? fread($fh, 8) : '';
        if ($fh) fclose($fh);
        $isZip = strncmp($head, "PK", 2) === 0;                                   // xlsx = arsip ZIP
        $isOle = strncmp($head, "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1", 8) === 0;      // xls  = OLE2
        if (($ext === 'xlsx' && !$isZip) || ($ext === 'xls' && !$isOle)) {
            return 'Berkas yang diunggah bukan file Excel yang sah.';
        }
        try {
            $ss = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return 'Gagal membaca file: ' . $e->getMessage();
        }
        $sheet      = $ss->getActiveSheet();
        $highestRow = $sheet->getHighestDataRow();
        $highestCol = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        $colOf = [];
        for ($c = 1; $c <= $highestCol; $c++) {
            $key = $this->normHeader((string) $sheet->getCell([$c, 1])->getValue(), $alias);
            if ($key !== '') $colOf[$key] = $c;
        }
        return [$sheet, $colOf, $highestRow];
    }

    /** Hasilkan & unduh file template .xlsx (header + 1 baris contoh + sheet Petunjuk). */
    protected function excelTemplate(string $filename, array $cols, array $contoh, array $petunjuk)
    {
        $ss = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $ss->getActiveSheet();
        $sheet->setTitle('Data');
        $col = 1;
        foreach ($cols as $h) { $sheet->setCellValue([$col, 1], $h); $col++; }
        $sheet->getStyle('A1:' . Coordinate::stringFromColumnIndex(count($cols)) . '1')->getFont()->setBold(true);
        $col = 1;
        foreach ($contoh as $v) {
            $sheet->setCellValueExplicit([$col, 2], (string) $v, DataType::TYPE_STRING);
            $col++;
        }
        for ($c = 1; $c <= count($cols); $c++) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($c))->setAutoSize(true);
        }
        $info = $ss->createSheet();
        $info->setTitle('Petunjuk');
        $r = 1;
        foreach ($petunjuk as $row) {
            $info->setCellValue([1, $r], $row[0]);
            $info->setCellValue([2, $r], $row[1] ?? '');
            $r++;
        }
        $info->getColumnDimension('A')->setWidth(28);
        $info->getColumnDimension('B')->setWidth(60);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($ss))->save('php://output');
        exit;
    }
}
