<?php

namespace App\Services;

use App\Models\KondisiEksternalModel;
use App\Services\Ai\GroqClient;
use Config\Database;
use RuntimeException;

/**
 * Sprint 3b Kriteria A: AI saran entri Kondisi Eksternal berdasarkan
 * konteks prodi (nama, jenjang, PT, kota). Output WAJIB di-review &
 * diverifikasi prodi (terutama sumber_referensi) sebelum disimpan.
 */
class KondisiEksternalSuggester
{
    public function isAiReady(): bool
    {
        return (new GroqClient())->isConfigured();
    }

    /** @return list<array{judul:string,deskripsi:string,dampak:string,respons_prodi:string,sumber_referensi:string}> */
    public function suggest(int $prodiId, string $kategori, int $n = 5): array
    {
        $info = KondisiEksternalModel::KATEGORI[$kategori] ?? null;
        if (!$info) throw new RuntimeException("Kategori '{$kategori}' tidak dikenali.");

        $ai = new GroqClient();
        if (!$ai->isConfigured()) {
            throw new RuntimeException('GROQ_API_KEY belum di-set di .env — fitur Saran AI tidak dapat berjalan.');
        }

        $ctx = $this->prodiContext($prodiId);
        $prompt = $this->buildPrompt($kategori, $info, $ctx, $n);

        $raw = $ai->chat([
            ['role' => 'system', 'content' => 'Anda asisten analis akreditasi LAM-Infokom Indonesia. Sarankan faktor Kondisi Eksternal yang relevan, faktual, dan kontekstual untuk prodi Indonesia. Kembalikan HANYA JSON array — tanpa penjelasan, tanpa markdown code block.'],
            ['role' => 'user',   'content' => $prompt],
        ], fast: false, temperature: 0.5);

        return $this->parseSuggestions($raw);
    }

    private function prodiContext(int $prodiId): array
    {
        $row = Database::connect()->table('prodi p')
            ->select('p.nama AS prodi_nama, p.jenjang, p.akreditasi_saat_ini,
                      u.nama AS upps_nama, u.jenis AS upps_jenis,
                      pt.nama AS pt_nama, pt.kota, pt.provinsi')
            ->join('upps u', 'u.id = p.upps_id', 'left')
            ->join('perguruan_tinggi pt', 'pt.id = u.perguruan_tinggi_id', 'left')
            ->where('p.id', $prodiId)->get()->getRowArray() ?: [];
        return $row;
    }

    private function buildPrompt(string $kat, array $info, array $ctx, int $n): string
    {
        $prodi = trim(($ctx['prodi_nama'] ?? '') . ' ' . ($ctx['jenjang'] ?? ''));
        $lokasi = trim(($ctx['kota'] ?? '') . ($ctx['provinsi'] ? ', ' . $ctx['provinsi'] : ''));

        return "Sarankan {$n} faktor Kondisi Eksternal pada kategori **{$info['label']}** ({$info['grup']}) yang RELEVAN untuk:\n\n"
            . "- Program Studi: {$prodi}\n"
            . "- Perguruan Tinggi: " . ($ctx['pt_nama'] ?? '-') . "\n"
            . ($ctx['upps_nama'] ? "- UPPS: " . ucfirst($ctx['upps_jenis'] ?? '') . " " . $ctx['upps_nama'] . "\n" : '')
            . "- Lokasi: " . ($lokasi ?: '-') . "\n"
            . "- Akreditasi saat ini: " . ($ctx['akreditasi_saat_ini'] ?? '-') . "\n"
            . "- Tahun konteks: " . date('Y') . "\n\n"
            . "Cakupan kategori: {$info['desc']}\n\n"
            . "Format output WAJIB: JSON array (TANPA markdown ```), tiap elemen object dengan:\n"
            . "- \"judul\": string singkat & spesifik (mis. nama peraturan, nama tren, isu konkret)\n"
            . "- \"deskripsi\": string 1-2 kalimat — apa faktornya & mengapa relevan untuk prodi\n"
            . "- \"dampak\": salah satu dari \"rendah\", \"sedang\", \"tinggi\"\n"
            . "- \"respons_prodi\": string — saran langkah konkret yang dapat dilakukan prodi merespons faktor ini\n"
            . "- \"sumber_referensi\": string — KOSONGKAN bila tidak yakin pasti. JANGAN MENGARANG sumber/nomor peraturan/tahun bila tidak yakin.\n\n"
            . "Pertimbangkan: keragaman tingkat dampak (jangan semua \"tinggi\"), keragaman sub-tema dalam kategori, kontekstualisasi ke jenjang & lokasi prodi.";
    }

    private function parseSuggestions(string $raw): array
    {
        $s = trim($raw);
        // Strip markdown code blocks bila AI tetap menyisipkan
        if (str_starts_with($s, '```')) {
            $s = preg_replace('/^```(json)?\s*/i', '', $s);
            $s = preg_replace('/\s*```$/', '', $s);
        }
        // Ekstrak [ ... ] terluar bila ada prefix/suffix
        $i = strpos($s, '['); $j = strrpos($s, ']');
        if ($i !== false && $j !== false && $j > $i) $s = substr($s, $i, $j - $i + 1);

        $arr = json_decode($s, true);
        if (!is_array($arr)) {
            throw new RuntimeException('AI mengembalikan format tidak valid (gagal parse JSON).');
        }

        $out = [];
        foreach ($arr as $it) {
            if (!is_array($it) || empty($it['judul']) || empty($it['deskripsi'])) continue;
            $dampak = strtolower(trim((string) ($it['dampak'] ?? 'sedang')));
            if (!in_array($dampak, ['rendah','sedang','tinggi'], true)) $dampak = 'sedang';
            $out[] = [
                'judul'            => mb_substr(trim((string) $it['judul']), 0, 250),
                'deskripsi'        => trim((string) $it['deskripsi']),
                'dampak'           => $dampak,
                'respons_prodi'    => trim((string) ($it['respons_prodi'] ?? '')),
                'sumber_referensi' => trim((string) ($it['sumber_referensi'] ?? '')),
            ];
        }
        return $out;
    }
}
