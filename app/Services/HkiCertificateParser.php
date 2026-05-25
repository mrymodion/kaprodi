<?php

namespace App\Services;

use App\Models\HkiModel;
use App\Services\Ai\GroqClient;

/**
 * Mengurai data HKI dari teks sertifikat (hasil ekstraksi PDF).
 * Hybrid: regex untuk template DJKI "Surat Pencatatan Ciptaan" (Hak Cipta),
 * lalu Groq AI sebagai fallback/penyempurna untuk format lain (Paten, dll).
 */
class HkiCertificateParser
{
    /** @return array{fields:array,source:string} */
    public function parse(string $text): array
    {
        $f = $this->parseRegex($text);
        $source = 'regex';

        // Bila field kunci belum lengkap, coba sempurnakan dengan AI (bila terkonfigurasi).
        if ((empty($f['judul']) || empty($f['no_hki'])) && (new GroqClient())->isConfigured()) {
            $ai = $this->parseAi($text);
            if ($ai) {
                foreach ($ai as $k => $v) {
                    if (($f[$k] ?? '') === '' && $v !== '') $f[$k] = $v;
                }
                $source = empty($this->parseRegex($text)['judul']) ? 'ai' : 'hybrid';
            }
        }
        return ['fields' => $f, 'source' => $source];
    }

    /** Ekstraksi berbasis pola untuk template DJKI Hak Cipta. */
    private function parseRegex(string $text): array
    {
        $t = preg_replace('/[ \t]{2,}/', ' ', $text); // rapikan spasi kolom, pertahankan newline

        $judul = $this->grab($t, 'Judul Ciptaan', ['Tanggal dan tempat', 'Jangka waktu', 'Nomor Pencatatan']);
        $jenisCiptaan = $this->grab($t, 'Jenis Ciptaan', ['Judul Ciptaan']);
        $pencipta = $this->grabAfter($t, 'Pencipta', 'Nama', 'Alamat');
        $pemegang = $this->grabAfter($t, 'Pemegang Hak Cipta', 'Nama', 'Alamat');

        $noHki = preg_match('/Nomor Pencatatan\s*:\s*([0-9]{4,})/i', $t, $m) ? $m[1] : '';
        if ($noHki === '' && preg_match('/Nomor (?:Paten|Permohonan)\s*:?\s*([A-Z0-9.\/-]{5,})/i', $t, $m)) {
            $noHki = trim($m[1]);
        }

        $tahun = '';
        if (preg_match('/Nomor dan tanggal permohonan\s*:\s*[A-Z0-9]+\s*,\s*[^\n]*?((?:19|20)\d{2})/i', $t, $m)) {
            $tahun = $m[1];
        } elseif (preg_match('/diumumkan[^:]*:\s*[^\n]*?((?:19|20)\d{2})/i', $t, $m)) {
            $tahun = $m[1];
        } elseif (preg_match('/((?:19|20)\d{2})/', $t, $m)) {
            $tahun = $m[1];
        }

        return [
            'judul'    => $this->clean($judul),
            'jenis'    => $this->mapJenis($text, $jenisCiptaan),
            'no_hki'   => $this->clean($noHki),
            'tahun'    => $tahun,
            'pemilik'  => $this->clean($pencipta ?: $pemegang),
            'pemegang' => $this->clean($pemegang),
            'status'   => 'granted',
        ];
    }

    /** Tangkap nilai setelah "Label :" hingga salah satu label batas berikutnya. */
    private function grab(string $t, string $label, array $boundaries): string
    {
        $bound = implode('|', array_map(fn($b) => preg_quote($b, '/'), $boundaries));
        $re = '/' . preg_quote($label, '/') . '\s*:\s*(.+?)\s*(?:' . $bound . '|$)/su';
        return preg_match($re, $t, $m) ? $m[1] : '';
    }

    /** Tangkap "Sub : value" yang muncul setelah sebuah "Section" (mis. Pencipta → Nama). */
    private function grabAfter(string $t, string $section, string $sub, string $boundary): string
    {
        $re = '/' . preg_quote($section, '/') . '\s+' . preg_quote($sub, '/')
            . '\s*:\s*(.+?)\s*(?:' . preg_quote($boundary, '/') . '|$)/su';
        return preg_match($re, $t, $m) ? $m[1] : '';
    }

    /** Tentukan jenis HKI dari isi sertifikat. */
    private function mapJenis(string $text, string $jenisCiptaan): string
    {
        $u = strtoupper($text);
        if (str_contains($u, 'PENCATATAN CIPTAAN') || str_contains($u, 'HAK CIPTA')) return 'hak_cipta';
        if (str_contains($u, 'PATEN SEDERHANA')) return 'paten_sederhana';
        if (str_contains($u, 'DESAIN INDUSTRI')) return 'desain_industri';
        if (str_contains($u, 'INDIKASI GEOGRAFIS')) return 'indikasi_geografis';
        if (str_contains($u, 'PATEN')) return 'paten';
        return 'hak_cipta';
    }

    /** Fallback: minta Groq mengembalikan JSON field HKI. */
    private function parseAi(string $text): ?array
    {
        $keys = implode(', ', array_keys(HkiModel::JENIS_LABELS));
        $sys = 'Anda mengekstrak data dari sertifikat HKI Indonesia. Jawab HANYA JSON valid '
            . '{"judul":"","jenis":"","no_hki":"","tahun":"","pemilik":"","status":""} tanpa teks lain. '
            . "Field 'jenis' WAJIB salah satu dari: {$keys}. "
            . "'no_hki' = Nomor Pencatatan/Paten. 'tahun' = tahun (YYYY). "
            . "'pemilik' = nama pencipta/inventor. 'status' = 'granted' atau 'terdaftar'.";
        $usr = "Teks sertifikat:\n" . mb_substr($text, 0, 4000);
        try {
            $raw = (new GroqClient())->chat(
                [['role' => 'system', 'content' => $sys], ['role' => 'user', 'content' => $usr]],
                false, 0.1
            );
        } catch (\Throwable $e) {
            return null;
        }
        if (!preg_match('/\{.*\}/s', $raw, $m)) return null;
        $j = json_decode($m[0], true);
        if (!is_array($j)) return null;
        $jenis = (string) ($j['jenis'] ?? '');
        return [
            'judul'   => trim((string) ($j['judul'] ?? '')),
            'jenis'   => array_key_exists($jenis, HkiModel::JENIS_LABELS) ? $jenis : '',
            'no_hki'  => trim((string) ($j['no_hki'] ?? '')),
            'tahun'   => preg_match('/((?:19|20)\d{2})/', (string) ($j['tahun'] ?? ''), $mm) ? $mm[1] : '',
            'pemilik' => trim((string) ($j['pemilik'] ?? '')),
            'status'  => ($j['status'] ?? '') === 'terdaftar' ? 'terdaftar' : 'granted',
        ];
    }

    private function clean(string $s): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $s));
    }
}
