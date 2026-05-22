<?php

namespace App\Services;

use Config\Database;
use Modules\Akreditasi\Engine\AkreditasiEngine;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

/**
 * Generator dokumen Word LED (Laporan Evaluasi Diri).
 *
 * Struktur LED:
 *  - Cover
 *  - Pendahuluan
 *  - Per-kriteria (C1–C6): Penetapan, Pelaksanaan, Evaluasi, Pengendalian, Peningkatan
 *  - Penutup
 *
 * Narasi diambil dari tabel led_narasi (tersimpan per prodi).
 * Jika field kosong, ditampilkan placeholder dari template_isi.
 * Groq AI di-trigger secara eksplisit dari controller dan disimpan ke led_narasi.
 */
class LedGenerator
{
    private \CodeIgniter\Database\BaseConnection $db;
    private AkreditasiEngine $engine;

    public function __construct()
    {
        $this->db     = Database::connect();
        $this->engine = new AkreditasiEngine();
    }

    // ── Public API ──────────────────────────────────────────────────────────

    /** Ambil semua template + narasi tersimpan untuk prodi. */
    public function getTemplatesWithNarasi(int $prodiId): array
    {
        $instrumen = $this->engine->getInstrumen($prodiId);
        if (!$instrumen) return [];

        $templates = $this->db->query("
            SELECT t.*, k.kode AS kriteria_kode, k.nama AS kriteria_nama
            FROM lam_template_led t
            LEFT JOIN lam_kriteria k ON k.id = t.lam_kriteria_id
            WHERE t.lam_instrumen_id = ?
            ORDER BY t.urutan
        ", [$instrumen['id']])->getResultArray();

        $narasiRows = $this->db->query("
            SELECT * FROM led_narasi WHERE prodi_id = ?
        ", [$prodiId])->getResultArray();

        $narasiMap = [];
        foreach ($narasiRows as $n) {
            $narasiMap[$n['lam_template_led_id']] = $n;
        }

        foreach ($templates as &$t) {
            $n = $narasiMap[$t['id']] ?? null;
            $t['narasi']           = $n['isi'] ?? null;
            $t['generated_by_ai']  = (bool)($n['generated_by_ai'] ?? false);
        }

        return $templates;
    }

    /** Generate file .docx LED dan return path. */
    public function generate(int $prodiId, ?int $taId = null): string
    {
        $instrumen = $this->engine->getInstrumen($prodiId);
        if (!$instrumen) {
            throw new \RuntimeException('Prodi belum memiliki instrumen LAM aktif.');
        }

        $prodi     = $this->db->table('prodi')->where('id', $prodiId)->get()->getRowArray();
        $templates = $this->getTemplatesWithNarasi($prodiId);
        $totalSkor = $this->engine->totalSkor($prodiId, $instrumen['id'], $taId);

        $phpWord = new PhpWord();
        $phpWord->getSettings()->setUpdateFields(true);

        // Global styles
        $phpWord->addFontStyle('heading1', ['bold' => true, 'size' => 16, 'color' => '0D3B8C']);
        $phpWord->addFontStyle('heading2', ['bold' => true, 'size' => 13, 'color' => '1A5276']);
        $phpWord->addFontStyle('heading3', ['bold' => true, 'size' => 11, 'color' => '2E4057']);
        $phpWord->addParagraphStyle('centered', ['alignment' => 'center', 'spaceAfter' => 120]);
        $phpWord->addParagraphStyle('body', ['spaceAfter' => 120, 'lineHeight' => 1.5]);

        $section = $phpWord->addSection([
            'marginTop'    => 1440,
            'marginBottom' => 1440,
            'marginLeft'   => 1800,
            'marginRight'  => 1440,
        ]);

        // ── Cover ──────────────────────────────────────────────────────────
        $this->addCover($section, $prodi, $instrumen, $totalSkor);

        // ── Susun bagian berdasarkan templates ─────────────────────────────
        $byBagian = [];
        foreach ($templates as $t) {
            $key = $t['bagian'] . ($t['kriteria_kode'] ? '_' . $t['kriteria_kode'] : '');
            $byBagian[$key][] = $t;
        }

        // Pendahuluan
        if (isset($byBagian['pendahuluan'])) {
            $section->addPageBreak();
            $section->addText('BAB I — PENDAHULUAN', 'heading1', 'centered');
            foreach ($byBagian['pendahuluan'] as $t) {
                $this->addNarasiBlock($section, $t);
            }
        }

        // Per-kriteria
        $babNum = 2;
        foreach (['C1', 'C2', 'C3', 'C4', 'C5', 'C6'] as $kode) {
            $key = 'kriteria_' . $kode;
            if (!isset($byBagian[$key])) continue;

            $namaKriteria = $byBagian[$key][0]['kriteria_nama'] ?? $kode;
            $section->addPageBreak();
            $section->addText("BAB {$babNum} — KRITERIA {$kode}: " . mb_strtoupper($namaKriteria), 'heading1', 'centered');
            $babNum++;

            foreach ($byBagian[$key] as $t) {
                $this->addNarasiBlock($section, $t);
            }
        }

        // Penutup
        if (isset($byBagian['penutup'])) {
            $section->addPageBreak();
            $section->addText("BAB {$babNum} — PENUTUP", 'heading1', 'centered');
            foreach ($byBagian['penutup'] as $t) {
                $this->addNarasiBlock($section, $t);
            }
        }

        // Simpan ke writable/led/
        $this->ensureLedDir();
        $filename = sprintf(
            'LED_%s_%s_%s.docx',
            preg_replace('/[^a-zA-Z0-9]/', '_', $prodi['kode'] ?? $prodi['nama']),
            date('Ymd_His'),
            $taId ? "TA{$taId}" : 'all'
        );
        $path = WRITEPATH . 'led/' . $filename;

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($path);

        return $path;
    }

    /**
     * Panggil Groq API untuk auto-generate narasi satu bagian.
     * Throws RuntimeException jika groq.apiKey tidak diset.
     */
    public function generateNarasiGroq(int $prodiId, int $templateId, string $extraContext = ''): string
    {
        $apiKey = env('groq.apiKey', '');
        if (empty($apiKey)) {
            throw new \RuntimeException('groq.apiKey belum dikonfigurasi di .env.');
        }

        $template = $this->db->table('lam_template_led')->where('id', $templateId)->get()->getRowArray();
        if (!$template) {
            throw new \RuntimeException("Template LED #{$templateId} tidak ditemukan.");
        }

        $prodi     = $this->db->table('prodi')->where('id', $prodiId)->get()->getRowArray();
        $prodiNama = $prodi['nama'] ?? 'program studi';
        $jenjang   = $prodi['jenjang'] ?? '';

        $instruksi = $template['prompt_groq']
            ?: "Tuliskan narasi bagian \"{$template['sub_bagian']}\" untuk \"{$template['judul']}\".";

        $prompt = "Konteks: Anda menulis bagian dari LED (Laporan Evaluasi Diri) "
            . "untuk akreditasi LAM INFOKOM 2.0 jenjang {$jenjang} pada program studi {$prodiNama}.\n\n"
            . "Tugas: {$instruksi}\n\n"
            . "Ketentuan: bahasa Indonesia formal akademik, faktual, 250-400 kata, "
            . "langsung ke isi narasi tanpa menjelaskan singkatan atau memberi basa-basi.";

        if ($extraContext) {
            $prompt .= "\n\nData pendukung program studi:\n" . $extraContext;
        }

        $client  = \Config\Services::curlrequest();
        $baseUrl = rtrim(env('groq.baseUrl', 'https://api.groq.com/openai/v1'), '/');
        $model   = env('groq.modelLong', 'llama-3.3-70b-versatile');

        $response = $client->request('POST', $baseUrl . '/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model'    => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah konsultan penjaminan mutu yang menyusun LED (Laporan Evaluasi Diri) program studi untuk akreditasi LAM INFOKOM di Indonesia. Dalam seluruh konteks ini, "LED" SELALU berarti "Laporan Evaluasi Diri" (sebuah dokumen akreditasi), dan TIDAK PERNAH berarti komponen elektronik (Light Emitting Diode). Tulis narasi dalam bahasa Indonesia yang formal, akademik, dan faktual.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.7,
                'max_tokens'  => 800,
            ],
            'timeout' => (int)env('groq.timeout', 60),
        ]);

        $body = json_decode($response->getBody(), true);

        if (!isset($body['choices'][0]['message']['content'])) {
            throw new \RuntimeException('Groq API tidak mengembalikan respons valid: ' . ($body['error']['message'] ?? 'unknown'));
        }

        return trim($body['choices'][0]['message']['content']);
    }

    /** Daftar file LED yang sudah ada di writable/led/. */
    public function listFiles(): array
    {
        $dir = WRITEPATH . 'led/';
        if (!is_dir($dir)) return [];

        $files = [];
        foreach (glob($dir . '*.docx') as $f) {
            $files[] = [
                'name'  => basename($f),
                'size'  => filesize($f),
                'mtime' => filemtime($f),
            ];
        }
        usort($files, fn($a, $b) => $b['mtime'] <=> $a['mtime']);
        return array_slice($files, 0, 10);
    }

    // ── Helpers ─────────────────────────────────────────────────────────────

    private function addCover(\PhpOffice\PhpWord\Element\Section $section, array $prodi, array $instrumen, array $totalSkor): void
    {
        $section->addText('LAPORAN EVALUASI DIRI (LED)', 'heading1', 'centered');
        $section->addText('PROGRAM STUDI', ['bold' => true, 'size' => 14], 'centered');
        $section->addText(strtoupper($prodi['nama'] ?? '-'), ['bold' => true, 'size' => 14, 'color' => '0D3B8C'], 'centered');
        $section->addTextBreak(1);

        $table = $section->addTable(['borderColor' => 'CCCCCC', 'borderSize' => 6, 'cellMarginLeft' => 80, 'cellMarginRight' => 80]);

        $info = [
            ['Program Studi',  $prodi['nama'] ?? '-'],
            ['Kode Prodi',     $prodi['kode'] ?? '-'],
            ['Jenjang',        $prodi['jenjang'] ?? '-'],
            ['Fakultas/UPPS',  $prodi['fakultas'] ?? $prodi['upps'] ?? '-'],
            ['Instrumen',      $instrumen['nama']],
            ['Predikat Saat Ini', $totalSkor['predikat'] . ' (' . $totalSkor['pct'] . '%)'],
            ['Tanggal Terbit', date('d-m-Y H:i:s')],
        ];

        foreach ($info as [$label, $value]) {
            $row = $table->addRow();
            $row->addCell(2500)->addText($label, ['bold' => true]);
            $row->addCell(100)->addText(':');
            $row->addCell(5000)->addText($value);
        }
    }

    private function addNarasiBlock(\PhpOffice\PhpWord\Element\Section $section, array $template): void
    {
        $section->addTextBreak(1);

        // Heading sub-bagian
        $subLabel = $template['sub_bagian'] ? ucfirst($template['sub_bagian']) : $template['judul'];
        $section->addText($subLabel, 'heading2');

        $isi = $template['narasi'] ?? $template['template_isi'] ?? '';

        if (!empty($isi)) {
            // Split paragraf
            foreach (explode("\n\n", $isi) as $para) {
                $para = trim($para);
                if ($para !== '') {
                    $section->addText($para, null, 'body');
                }
            }
        } else {
            $section->addText('[Narasi belum diisi. Klik "Generate AI" atau isi manual.]', ['italic' => true, 'color' => '999999'], 'body');
        }

        if ($template['generated_by_ai']) {
            $section->addText('— Dihasilkan otomatis oleh AI (Groq). Harap ditinjau kembali.', ['italic' => true, 'size' => 9, 'color' => 'AAAAAA']);
        }
    }

    private function ensureLedDir(): void
    {
        $dir = WRITEPATH . 'led/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
    }
}
