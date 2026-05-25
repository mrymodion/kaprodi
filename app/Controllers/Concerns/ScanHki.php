<?php

namespace App\Controllers\Concerns;

use App\Services\HkiCertificateParser;
use App\Services\PdfTextExtractor;

/**
 * Endpoint AJAX: baca sertifikat HKI (PDF) → kembalikan field terisi (draf).
 * Dipakai bersama oleh form HKI dosen & admin.
 */
trait ScanHki
{
    protected function scanHkiResponse()
    {
        $csrf = ['csrfName' => csrf_token(), 'csrfHash' => csrf_hash()];

        // Jalur utama untuk shared hosting: teks sudah diekstrak di browser (PDF.js).
        $teks = trim((string) $this->request->getPost('teks'));
        if (mb_strlen($teks) >= 20) {
            $res = (new HkiCertificateParser())->parse(mb_substr($teks, 0, 20000));
            return $this->response->setJSON(['ok' => true, 'fields' => $res['fields'], 'source' => $res['source']] + $csrf);
        }

        // Jalur cadangan: server mengekstrak sendiri (butuh pdftotext / smalot).
        $file = $this->request->getFile('berkas');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Berkas tidak valid.'] + $csrf);
        }
        $ext = strtolower($file->getClientExtension() ?: $file->getExtension());
        if ($ext !== 'pdf') {
            return $this->response->setJSON(['ok' => false, 'error' => 'Hanya berkas PDF yang didukung.'] + $csrf);
        }
        $fh = @fopen($file->getTempName(), 'rb');
        $head = $fh ? fread($fh, 5) : '';
        if ($fh) fclose($fh);
        if (strncmp($head, '%PDF-', 5) !== 0) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Berkas bukan PDF yang sah.'] + $csrf);
        }

        $extractor = new PdfTextExtractor();
        if (!$extractor->available()) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Fitur baca PDF belum tersedia di server (pdftotext belum terpasang). Isi manual.'] + $csrf);
        }
        $text = $extractor->extract($file->getTempName());
        if ($text === null || mb_strlen($text) < 20) {
            return $this->response->setJSON(['ok' => false, 'error' => 'Teks tidak terbaca dari PDF (kemungkinan hasil scan/gambar). Silakan isi manual.'] + $csrf);
        }

        $res = (new HkiCertificateParser())->parse($text);
        return $this->response->setJSON([
            'ok'     => true,
            'fields' => $res['fields'],
            'source' => $res['source'],
        ] + $csrf);
    }
}
