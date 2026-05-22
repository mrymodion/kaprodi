<?php

namespace App\Controllers\Concerns;

use PhpOffice\PhpSpreadsheet\Shared\Date as XlsDate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Helper umum untuk fitur import Excel (normalisasi header & parsing tanggal).
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
}
