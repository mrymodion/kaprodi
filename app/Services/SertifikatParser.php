<?php

namespace App\Services;

use App\Models\RekognisiDosenModel;
use App\Services\Ai\GroqClient;

/**
 * Mengurai data dari teks sertifikat/surat untuk form Rekognisi & Pengembangan Diri.
 * Dokumen sangat beragam (pelatihan, sertifikasi, surat tugas, penghargaan), jadi
 * AI (Groq) dipakai bila terkonfigurasi, dengan heuristik regex sebagai cadangan
 * agar tetap mengisi sebagian field meski tanpa AI.
 */
class SertifikatParser
{
    /** @return array{fields:array,source:string} */
    public function parseRekognisi(string $text): array
    {
        $f = $this->heuristikRekognisi($text);
        $source = 'regex';

        if ((empty($f['judul']) || empty($f['penyelenggara'])) && (new GroqClient())->isConfigured()) {
            $ai = $this->ai($text, 'rekognisi');
            if ($ai) {
                foreach ($ai as $k => $v) {
                    if (($f[$k] ?? '') === '' && $v !== '') $f[$k] = $v;
                }
                $source = $f['judul'] === '' ? 'ai' : 'hybrid';
                // jenis & tingkat dari AI lebih akurat → timpa bila valid
                if (!empty($ai['jenis'])) $f['jenis'] = $ai['jenis'];
                if (!empty($ai['tingkat'])) $f['tingkat'] = $ai['tingkat'];
            }
        }
        return ['fields' => $f, 'source' => $source];
    }

    /** @return array{fields:array,source:string} */
    public function parsePengembangan(string $text): array
    {
        $f = $this->heuristikPengembangan($text);
        $source = 'regex';

        if (empty($f['jenis_pengembangan']) && (new GroqClient())->isConfigured()) {
            $ai = $this->ai($text, 'pengembangan');
            if ($ai) {
                if (!empty($ai['jenis_pengembangan'])) $f['jenis_pengembangan'] = $ai['jenis_pengembangan'];
                if ($f['tahun'] === '' && !empty($ai['tahun'])) $f['tahun'] = $ai['tahun'];
                $source = 'ai';
            }
        }
        return ['fields' => $f, 'source' => $source];
    }

    // ───────────────────────── Heuristik ─────────────────────────

    private function heuristikRekognisi(string $text): array
    {
        $t = $this->norm($text);
        return [
            'judul'         => $this->judul($t),
            'jenis'         => $this->mapJenis($t),
            'penyelenggara' => $this->penyelenggara($t),
            'tingkat'       => $this->tingkat($t),
            'tahun'         => $this->tahun($t),
        ];
    }

    private function heuristikPengembangan(string $text): array
    {
        $t = $this->norm($text);
        return [
            'jenis_pengembangan' => $this->judul($t),
            'tahun'              => $this->tahun($t),
        ];
    }

    /** Tebak judul/nama kegiatan dari baris yang memuat kata kunci. */
    private function judul(string $t): string
    {
        // Prioritas: baris kegiatan/peran (lebih informatif daripada header "SERTIFIKAT").
        $primary = '/[^\n]*\b(?:pelatihan|workshop|seminar|webinar|konferensi|conference|'
            . 'training|bimbingan teknis|bimtek|kursus|course|penghargaan|award|juara|'
            . 'keynote|invited speaker|narasumber|reviewer|editor|asesor|visiting)\b[^\n]*/iu';
        $best = $this->bestLine($t, $primary);
        if ($best !== '') return $best;

        // Cadangan: baris dengan kata jenis dokumen.
        return $this->bestLine($t, '/[^\n]*\b(?:sertifikat|certificate|piagam)\b[^\n]*/iu');
    }

    /** Ambil baris terpanjang (paling informatif) yang cocok pola, abaikan baris pendek. */
    private function bestLine(string $t, string $re): string
    {
        if (!preg_match_all($re, $t, $m)) return '';
        $best = '';
        foreach ($m[0] as $line) {
            $line = $this->clean($line);
            if (mb_strlen($line) > mb_strlen($best)) $best = $line;
        }
        return mb_strlen($best) >= 6 ? $best : '';
    }

    private function penyelenggara(string $t): string
    {
        $pola = [
            '/diselenggarakan oleh\s*:?\s*([^\n]+)/iu',
            '/penyelenggara\s*:?\s*([^\n]+)/iu',
            '/diterbitkan oleh\s*:?\s*([^\n]+)/iu',
            '/organized by\s*:?\s*([^\n]+)/iu',
        ];
        foreach ($pola as $re) {
            if (preg_match($re, $t, $m)) return $this->clean($m[1]);
        }
        return '';
    }

    private function tingkat(string $t): string
    {
        $u = strtolower($t);
        if (preg_match('/internasional|international/u', $u)) return 'internasional';
        if (preg_match('/nasional|national/u', $u)) return 'nasional';
        return '';
    }

    private function mapJenis(string $t): string
    {
        $u = strtolower($t);
        $map = [
            'reviewer_jurnal'  => ['reviewer', 'mitra bestari', 'peer review'],
            'editor_jurnal'    => ['editor', 'dewan redaksi', 'editorial board'],
            'keynote_speaker'  => ['keynote', 'invited speaker', 'pembicara kunci'],
            'pemakalah'        => ['pemakalah', 'presenter', 'oral presentation'],
            'asesor'           => ['asesor', 'assessor'],
            'narasumber'       => ['narasumber', 'tenaga ahli', 'resource person'],
            'visiting_scholar' => ['visiting scholar', 'dosen tamu', 'visiting lecturer'],
            'penghargaan'      => ['penghargaan', 'award', 'juara', 'winner', 'piagam'],
            'pengurus_organisasi' => ['pengurus', 'ketua umum', 'sekretaris jenderal', 'organisasi profesi'],
        ];
        foreach ($map as $key => $kws) {
            foreach ($kws as $kw) {
                if (str_contains($u, $kw)) return $key;
            }
        }
        return '';
    }

    private function tahun(string $t): string
    {
        if (preg_match('/\b(19|20)\d{2}\b/u', $t, $m)) return $m[0];
        return '';
    }

    // ───────────────────────── AI ─────────────────────────

    private function ai(string $text, string $type): ?array
    {
        if ($type === 'rekognisi') {
            $keys = implode(', ', array_keys(RekognisiDosenModel::JENIS_LABELS));
            $sys = 'Anda mengekstrak data dari sertifikat/surat rekognisi dosen Indonesia. '
                . 'Jawab HANYA JSON valid {"judul":"","jenis":"","penyelenggara":"","tingkat":"","tahun":""} tanpa teks lain. '
                . "'judul' = nama kegiatan/peran (mis. 'Reviewer Jurnal X', 'Keynote Speaker Konferensi Y'). "
                . "'jenis' WAJIB salah satu dari: {$keys}. "
                . "'penyelenggara' = institusi/penyelenggara. "
                . "'tingkat' WAJIB salah satu: lokal, nasional, internasional. "
                . "'tahun' = tahun (YYYY).";
        } else {
            $sys = 'Anda mengekstrak data dari sertifikat pelatihan/sertifikasi/workshop dosen Indonesia. '
                . 'Jawab HANYA JSON valid {"jenis_pengembangan":"","tahun":""} tanpa teks lain. '
                . "'jenis_pengembangan' = nama kegiatan/sertifikasi (mis. 'Sertifikasi AWS Cloud Practitioner', "
                . "'Workshop Data Science'). 'tahun' = tahun (YYYY).";
        }
        $usr = "Teks dokumen:\n" . mb_substr($text, 0, 4000);

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

        if ($type === 'rekognisi') {
            $jenis   = (string) ($j['jenis'] ?? '');
            $tingkat = (string) ($j['tingkat'] ?? '');
            return [
                'judul'         => $this->clean((string) ($j['judul'] ?? '')),
                'jenis'         => array_key_exists($jenis, RekognisiDosenModel::JENIS_LABELS) ? $jenis : '',
                'penyelenggara' => $this->clean((string) ($j['penyelenggara'] ?? '')),
                'tingkat'       => in_array($tingkat, ['lokal', 'nasional', 'internasional'], true) ? $tingkat : '',
                'tahun'         => preg_match('/(19|20)\d{2}/', (string) ($j['tahun'] ?? ''), $mm) ? $mm[0] : '',
            ];
        }
        return [
            'jenis_pengembangan' => $this->clean((string) ($j['jenis_pengembangan'] ?? '')),
            'tahun'              => preg_match('/(19|20)\d{2}/', (string) ($j['tahun'] ?? ''), $mm) ? $mm[0] : '',
        ];
    }

    private function norm(string $s): string
    {
        return (string) preg_replace('/[ \t]{2,}/', ' ', $s);
    }

    private function clean(string $s): string
    {
        return trim((string) preg_replace('/\s+/', ' ', $s));
    }
}
