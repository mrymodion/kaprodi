<?php

namespace App\Controllers;

use App\Services\Ai\GroqClient;
use Config\Database;

/**
 * Endpoint bantuan Groq AI untuk membuat draf naratif (JSON response):
 *  - vmts                : draf Visi/Misi/Tujuan/Strategi
 *  - tupoksi             : draf tugas pokok & fungsi
 *  - dampak_pkm          : draf dampak/luaran PkM
 *  - manfaat_kerjasama   : draf manfaat kerjasama
 *  - ringkasan           : ringkasan eksekutif prodi
 */
class AiController extends BaseController
{
    private function prodi(): ?array
    {
        $pid = (int) session('active_prodi_id');
        $db  = Database::connect();
        if (!$pid && session('is_superadmin')) {
            $first = $db->table('prodi')->where('status', 'aktif')->orderBy('nama')->get()->getRowArray();
            $pid = $first ? (int) $first['id'] : 0;
        }
        if (!$pid) return null;
        return $db->table('prodi p')
            ->select('p.*, u.nama AS upps_nama, pt.nama AS pt_nama')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->where('p.id', $pid)->get()->getRowArray();
    }

    public function draft(string $jenis)
    {
        $client = new GroqClient();
        if (!$client->isConfigured()) {
            return $this->response->setJSON(['error' => 'Groq AI belum dikonfigurasi (set groq.apiKey di .env).']);
        }
        $prodi = $this->prodi();
        if (!$prodi) {
            return $this->response->setJSON(['error' => 'Prodi aktif belum dipilih.']);
        }
        $req  = $this->request;
        $nama = $prodi['nama'] ?? 'program studi';
        $jenjang = $prodi['jenjang'] ?? '';
        $pt   = $prodi['pt_nama'] ?? '';

        try {
            if ($jenis === 'vmts') {
                $sys = 'Anda konsultan penjaminan mutu perguruan tinggi di Indonesia. Buat draf Visi, Misi, Tujuan, dan Strategi (VMTS) program studi yang realistis, selaras, dan dapat diukur. Jawab HANYA dengan JSON valid berformat {"visi":"...","misi":"...","tujuan":"...","strategi":"..."} tanpa teks lain. Misi/Tujuan/Strategi boleh berupa poin yang dipisah baris baru.';
                $usr = "Program studi: {$nama} ({$jenjang}) di {$pt}. Buat VMTS dalam bahasa Indonesia formal akademik.";
                $raw = $client->chat([['role' => 'system', 'content' => $sys], ['role' => 'user', 'content' => $usr]], false, 0.6);
                $json = $this->extractJson($raw);
                if ($json && isset($json['visi'])) {
                    return $this->response->setJSON(['ok' => true, 'vmts' => [
                        'visi' => trim((string) ($json['visi'] ?? '')),
                        'misi' => trim((string) ($json['misi'] ?? '')),
                        'tujuan' => trim((string) ($json['tujuan'] ?? '')),
                        'strategi' => trim((string) ($json['strategi'] ?? '')),
                    ]]);
                }
                return $this->response->setJSON(['ok' => true, 'vmts' => ['visi' => trim($raw), 'misi' => '', 'tujuan' => '', 'strategi' => '']]);
            }

            [$sys, $usr] = $this->prompt($jenis, $nama, $jenjang, $pt, $prodi);
            if (!$sys) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis draf tidak dikenal.']);
            }
            $text = $client->chat([['role' => 'system', 'content' => $sys], ['role' => 'user', 'content' => $usr]], false, 0.5);
            return $this->response->setJSON(['ok' => true, 'text' => trim($text)]);
        } catch (\Throwable $e) {
            log_message('error', 'AI draft error: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Gagal menghubungi Groq AI.']);
        }
    }

    private function prompt(string $jenis, string $nama, string $jenjang, string $pt, array $prodi): array
    {
        $req = $this->request;
        $formal = 'Tulis dalam bahasa Indonesia formal akademik, langsung ke isi tanpa basa-basi.';
        switch ($jenis) {
            case 'tupoksi':
                $unit = trim((string) $req->getPost('unit')); $jab = trim((string) $req->getPost('jabatan'));
                return [
                    "Anda penyusun dokumen tata pamong perguruan tinggi. {$formal} Buat uraian Tugas Pokok dan Fungsi (3-6 poin) ringkas.",
                    "Program studi {$nama} ({$jenjang}). Unit/jabatan: " . ($jab ?: $unit ?: 'pimpinan prodi') . ". Tuliskan tupoksi-nya.",
                ];
            case 'dampak_pkm':
                $judul = trim((string) $req->getPost('judul'));
                return [
                    "Anda penyusun laporan PkM. {$formal} Tuliskan dampak/luaran kegiatan PkM (2-4 kalimat).",
                    "Prodi {$nama}. Judul PkM: " . ($judul ?: '(umum)') . ". Tuliskan dampak dan luaran yang dihasilkan bagi mitra/masyarakat.",
                ];
            case 'manfaat_kerjasama':
                $mitra = trim((string) $req->getPost('mitra')); $bidang = trim((string) $req->getPost('bidang'));
                return [
                    "Anda penyusun dokumen kerjasama akademik. {$formal} Tuliskan manfaat kerjasama (2-4 kalimat).",
                    "Prodi {$nama}. Mitra: " . ($mitra ?: '(mitra)') . ". Bidang: " . ($bidang ?: 'tridharma') . ". Tuliskan manfaat kerjasama bagi prodi dan mitra.",
                ];
            case 'ringkasan':
                return [
                    "Anda penyusun Ringkasan Eksekutif Laporan Evaluasi Diri (LED) akreditasi. {$formal} Buat ringkasan 3-5 paragraf yang menonjolkan keunggulan, capaian, dan rencana pengembangan prodi.",
                    "Ringkasan eksekutif untuk {$nama} ({$jenjang}) di {$pt}.\n\nData pendukung:\n" . $this->ringkasanContext((int) $prodi['id']),
                ];
            default:
                return [null, null];
        }
    }

    /** Konteks ringkas untuk ringkasan eksekutif. */
    private function ringkasanContext(int $prodiId): string
    {
        $db = Database::connect();
        $engine = new \Modules\Akreditasi\Engine\AkreditasiEngine();
        $instrumen = $engine->getInstrumen($prodiId);
        $lines = [];
        if ($instrumen) {
            $t = $engine->totalSkor($prodiId, $instrumen['id'], null);
            $lines[] = sprintf('Nilai akreditasi sementara: %.1f/%.0f (%.1f%%), predikat %s.', $t['nilai_akhir'], $t['max_nilai'], $t['pct'], $t['predikat']);
        }
        $lines[] = 'DTPR aktif: ' . (int) $db->table('dosen')->where(['prodi_id' => $prodiId, 'aktif' => 1])->countAllResults() . ' dosen.';
        $lines[] = 'Mahasiswa aktif: ' . (int) $db->table('mahasiswa')->where(['prodi_id' => $prodiId, 'status' => 'aktif'])->countAllResults() . '.';
        $lines[] = 'Penelitian: ' . (int) $db->table('penelitian')->where('prodi_id', $prodiId)->countAllResults()
                 . '; PkM: ' . (int) $db->table('pkm')->where('prodi_id', $prodiId)->countAllResults()
                 . '; Publikasi: ' . (int) $db->table('publikasi')->where('prodi_id', $prodiId)->countAllResults() . '.';
        $tracer = $db->query("SELECT AVG(ts.masa_tunggu_bulan) a, COUNT(*) n FROM tracer_study ts JOIN mahasiswa m ON m.id=ts.mahasiswa_id WHERE m.prodi_id=?", [$prodiId])->getRow();
        if ($tracer && (int) $tracer->n > 0) {
            $lines[] = sprintf('Tracer study: %d responden, rata-rata masa tunggu %.1f bulan.', (int) $tracer->n, (float) $tracer->a);
        }
        return implode("\n", $lines);
    }

    private function extractJson(string $raw): ?array
    {
        if (!preg_match('/\{.*\}/s', $raw, $m)) {
            return null;
        }
        $jsonStr = $m[0];
        $d = json_decode($jsonStr, true);
        if (is_array($d)) {
            return $d;
        }
        // Model sering menaruh baris-baru/tab MENTAH di dalam nilai string
        // (mis. misi/tujuan poin per baris), yang membuat JSON tidak valid.
        // Escape karakter kontrol di dalam string lalu coba decode lagi.
        $d = json_decode($this->escapeControlInStrings($jsonStr), true);
        return is_array($d) ? $d : null;
    }

    /** Escape karakter kontrol mentah (newline/CR/tab) yang berada di dalam nilai string JSON. */
    private function escapeControlInStrings(string $s): string
    {
        $out = '';
        $inStr = false;
        $esc = false;
        $len = strlen($s);
        for ($i = 0; $i < $len; $i++) {
            $c = $s[$i];
            if ($inStr) {
                if ($esc) { $out .= $c; $esc = false; continue; }
                if ($c === '\\') { $out .= $c; $esc = true; continue; }
                if ($c === '"') { $out .= $c; $inStr = false; continue; }
                if ($c === "\n") { $out .= '\\n'; continue; }
                if ($c === "\r") { $out .= '\\r'; continue; }
                if ($c === "\t") { $out .= '\\t'; continue; }
                $out .= $c;
            } else {
                $out .= $c;
                if ($c === '"') { $inStr = true; }
            }
        }
        return $out;
    }
}
