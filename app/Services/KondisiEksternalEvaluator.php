<?php

namespace App\Services;

use App\Models\KondisiEksternalModel;
use App\Models\KondisiEksternalNarasiModel;
use App\Services\Ai\GroqClient;
use RuntimeException;

/**
 * Sprint 4 Kriteria A: Audit mutu otomatis Kondisi Eksternal.
 *
 * Pendekatan hybrid:
 * - Heuristik deterministik untuk 6 dimensi (instan, gratis)
 * - AI Judge (opsional) untuk 3 dimensi kualitatif (kedalaman,
 *   respons_prodi, sintesis_led) — override skor heuristik bila
 *   diaktifkan
 *
 * Output: skor per dimensi (1-4), skor total terbobot, label
 * akreditasi, dan daftar rekomendasi konkret yang ranked by priority.
 */
class KondisiEksternalEvaluator
{
    /** Bobot per dimensi (total 10). */
    public const BOBOT = [
        'kelengkapan_kategori' => 2.0,
        'kedalaman_analisis'   => 2.0,
        'keseimbangan_swot'    => 1.0,
        'kelengkapan_bukti'    => 1.5,
        'respons_prodi'        => 1.5,
        'sintesis_led'         => 2.0,
    ];

    /** Metadata per dimensi: [label, ikon]. */
    public const META = [
        'kelengkapan_kategori' => ['Kelengkapan Kategori',    'bi-grid-3x3-gap'],
        'kedalaman_analisis'   => ['Kedalaman Analisis',      'bi-zoom-in'],
        'keseimbangan_swot'    => ['Keseimbangan SWOT-Ext',   'bi-bar-chart-steps'],
        'kelengkapan_bukti'    => ['Kelengkapan Bukti',       'bi-paperclip'],
        'respons_prodi'        => ['Respons Prodi',           'bi-lightning-charge'],
        'sintesis_led'         => ['Sintesis Narasi LED',     'bi-file-text'],
    ];

    /** Dimensi yang ditingkatkan AI Judge bila diaktifkan. */
    public const AI_DIMENSI = ['kedalaman_analisis', 'respons_prodi', 'sintesis_led'];

    public function isAiReady(): bool
    {
        return (new GroqClient())->isConfigured();
    }

    public function evaluate(int $prodiId, bool $withAi = false): array
    {
        $entries = (new KondisiEksternalModel())
            ->where('prodi_id', $prodiId)->orderBy('kategori, id')->findAll();
        $grouped = [];
        foreach ($entries as $e) $grouped[$e['kategori']][] = $e;

        $narasi = (new KondisiEksternalNarasiModel())->byProdi($prodiId);

        $heur = [
            'kelengkapan_kategori' => $this->skorKategori($grouped),
            'kedalaman_analisis'   => $this->skorKedalaman($entries),
            'keseimbangan_swot'    => $this->skorSwot($grouped),
            'kelengkapan_bukti'    => $this->skorBukti($entries),
            'respons_prodi'        => $this->skorRespons($entries),
            'sintesis_led'         => $this->skorSintesis($narasi),
        ];
        $detail = [
            'kelengkapan_kategori' => $this->detailKategori($grouped),
            'kedalaman_analisis'   => $this->detailKedalaman($entries),
            'keseimbangan_swot'    => $this->detailSwot($grouped),
            'kelengkapan_bukti'    => $this->detailBukti($entries),
            'respons_prodi'        => $this->detailRespons($entries),
            'sintesis_led'         => $this->detailSintesis($narasi),
        ];

        $ai = $withAi ? $this->aiJudge($grouped, $narasi) : [];

        // Final per dimensi: AI override bila tersedia, kalau tidak heuristik
        $final = [];
        foreach (array_keys(self::BOBOT) as $dim) {
            $final[$dim] = $ai[$dim]['skor'] ?? $heur[$dim];
        }

        // Total terbobot
        $sum = 0; $totalBobot = 0;
        foreach (self::BOBOT as $dim => $b) {
            $sum += $final[$dim] * $b;
            $totalBobot += $b;
        }
        $skorTotal = $totalBobot > 0 ? round($sum / $totalBobot, 2) : 0;

        // Rekomendasi: dimensi <4, sorted ascending (priority)
        $rekomendasi = [];
        foreach ($final as $dim => $skor) {
            if ($skor < 4) {
                $rekomendasi[] = [
                    'dim'   => $dim,
                    'label' => self::META[$dim][0],
                    'skor'  => $skor,
                    'saran' => $this->saran($dim, $skor, $detail[$dim] ?? []),
                ];
            }
        }
        usort($rekomendasi, fn($a, $b) => $a['skor'] <=> $b['skor']);

        return [
            'heuristik'   => $heur,
            'ai'          => $ai,
            'detail'      => $detail,
            'final'       => $final,
            'skor_total'  => $skorTotal,
            'label'       => $this->labelKlasifikasi($skorTotal),
            'rekomendasi' => $rekomendasi,
            'with_ai'     => $withAi,
            'n_entries'   => count($entries),
        ];
    }

    // ───────────────────── Heuristik per dimensi ─────────────────────

    private function skorKategori(array $grouped): int
    {
        $n = 0;
        foreach (KondisiEksternalModel::KATEGORI as $k => $_) if (!empty($grouped[$k])) $n++;
        return $n >= 8 ? 4 : ($n >= 6 ? 3 : ($n >= 4 ? 2 : 1));
    }
    private function detailKategori(array $grouped): array
    {
        $n = 0;
        $kosong = [];
        foreach (KondisiEksternalModel::KATEGORI as $k => $info) {
            if (!empty($grouped[$k])) $n++;
            else $kosong[] = $info['label'];
        }
        return ['terisi' => $n, 'total' => count(KondisiEksternalModel::KATEGORI), 'kosong' => $kosong];
    }

    private function skorKedalaman(array $entries): int
    {
        if (empty($entries)) return 1;
        $avg = array_sum(array_map(fn($e) => mb_strlen((string) $e['deskripsi']), $entries)) / count($entries);
        return $avg > 150 ? 4 : ($avg > 80 ? 3 : ($avg > 40 ? 2 : 1));
    }
    private function detailKedalaman(array $entries): array
    {
        if (empty($entries)) return ['avg_char' => 0, 'n' => 0];
        $avg = array_sum(array_map(fn($e) => mb_strlen((string) $e['deskripsi']), $entries)) / count($entries);
        return ['avg_char' => (int) round($avg), 'n' => count($entries)];
    }

    private function skorSwot(array $grouped): int
    {
        $p = count($grouped['peluang'] ?? []); $a = count($grouped['ancaman'] ?? []);
        if ($p >= 3 && $a >= 3) return 4;
        if ($p >= 2 && $a >= 2) return 3;
        if ($p >= 1 && $a >= 1) return 2;
        return 1;
    }
    private function detailSwot(array $grouped): array
    {
        return ['peluang' => count($grouped['peluang'] ?? []), 'ancaman' => count($grouped['ancaman'] ?? [])];
    }

    private function skorBukti(array $entries): int
    {
        if (empty($entries)) return 1;
        $with = count(array_filter($entries, fn($e) => !empty($e['link_bukti'])));
        $pct = $with / count($entries);
        return $pct >= 0.8 ? 4 : ($pct >= 0.5 ? 3 : ($pct >= 0.25 ? 2 : 1));
    }
    private function detailBukti(array $entries): array
    {
        if (empty($entries)) return ['pct' => 0, 'with' => 0, 'total' => 0];
        $with = count(array_filter($entries, fn($e) => !empty($e['link_bukti'])));
        return ['pct' => (int) round(($with / count($entries)) * 100), 'with' => $with, 'total' => count($entries)];
    }

    private function skorRespons(array $entries): int
    {
        $tinggi = array_values(array_filter($entries, fn($e) => $e['dampak'] === 'tinggi'));
        $set = !empty($tinggi) ? $tinggi : $entries;
        if (empty($set)) return 1;
        $with = count(array_filter($set, fn($e) => !empty($e['respons_prodi'])));
        $pct = $with / count($set);
        return $pct >= 0.8 ? 4 : ($pct >= 0.5 ? 3 : ($pct >= 0.25 ? 2 : 1));
    }
    private function detailRespons(array $entries): array
    {
        $tinggi = array_values(array_filter($entries, fn($e) => $e['dampak'] === 'tinggi'));
        $set = !empty($tinggi) ? $tinggi : $entries;
        if (empty($set)) return ['pct' => 0, 'with' => 0, 'total' => 0, 'fokus' => 'semua'];
        $with = count(array_filter($set, fn($e) => !empty($e['respons_prodi'])));
        return ['pct' => (int) round(($with / count($set)) * 100), 'with' => $with, 'total' => count($set),
                'fokus' => !empty($tinggi) ? 'dampak tinggi' : 'semua'];
    }

    private function skorSintesis(array $narasi): int
    {
        $n = 0;
        foreach (['A.1','A.2','A.3','A.4'] as $sub) if (!empty($narasi[$sub]['narasi'])) $n++;
        return max(1, $n);
    }
    private function detailSintesis(array $narasi): array
    {
        $list = [];
        foreach (['A.1','A.2','A.3','A.4'] as $sub) $list[$sub] = !empty($narasi[$sub]['narasi']);
        return $list;
    }

    // ───────────────────── AI Judge ─────────────────────

    private function aiJudge(array $grouped, array $narasi): array
    {
        $ai = new GroqClient();
        if (!$ai->isConfigured()) {
            throw new RuntimeException('GROQ_API_KEY belum di-set di .env — AI Judge tidak dapat berjalan.');
        }

        // Konteks ringkas agar prompt tidak terlalu panjang
        $ctx = [];
        foreach ($grouped as $kat => $rows) {
            $ctx[$kat] = array_map(fn($r) => [
                'judul'         => $r['judul'],
                'deskripsi'     => mb_strimwidth((string) $r['deskripsi'], 0, 250, '…'),
                'dampak'        => $r['dampak'],
                'respons_prodi' => $r['respons_prodi'] ? mb_strimwidth((string) $r['respons_prodi'], 0, 150, '…') : null,
            ], $rows);
        }
        $narasiSum = [];
        foreach (['A.1','A.2','A.3','A.4'] as $sub) {
            if (!empty($narasi[$sub]['narasi'])) {
                $narasiSum[$sub] = mb_strimwidth((string) $narasi[$sub]['narasi'], 0, 600, '…');
            }
        }

        $prompt = "Anda asesor LAM-Infokom yang menilai analisis Kondisi Eksternal Program Studi.\n\n"
            . "DATA INPUT PRODI (per kategori):\n" . json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n"
            . "NARASI LED YANG SUDAH DISUSUN (per sub-bagian):\n" . json_encode($narasiSum, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n"
            . "TUGAS: Nilai 3 dimensi berikut pada skala 1-4 (1=Kurang, 2=Cukup, 3=Baik, 4=Sangat Baik) berdasarkan rubrik:\n\n"
            . "1. **kedalaman_analisis** — apakah analisis substantif & mendalam (bukan hanya menyebut nama tanpa elaborasi konteks/dampak)?\n"
            . "2. **respons_prodi** — apakah respons prodi konkret, terukur, dapat ditindaklanjuti (bukan retorik generic seperti 'meningkatkan kualitas')?\n"
            . "3. **sintesis_led** — apakah narasi LED koheren, mengintegrasikan data, mengalir, dan menunjukkan implikasi strategis?\n\n"
            . "Output WAJIB JSON object (TANPA markdown code-block), format:\n"
            . "{\"kedalaman_analisis\":{\"skor\":N,\"justifikasi\":\"1-2 kalimat\"},\"respons_prodi\":{\"skor\":N,\"justifikasi\":\"1-2 kalimat\"},\"sintesis_led\":{\"skor\":N,\"justifikasi\":\"1-2 kalimat\"}}\n\n"
            . "Bersikap kritis & objektif. JANGAN inflasi skor — Sangat Baik (4) untuk yang benar-benar luar biasa, bukan default.";

        $raw = $ai->chat([
            ['role' => 'system', 'content' => 'Anda asesor akreditasi LAM-Infokom yang objektif & kritis. Kembalikan HANYA JSON object.'],
            ['role' => 'user',   'content' => $prompt],
        ], fast: false, temperature: 0.2);

        return $this->parseAiJudge($raw);
    }

    private function parseAiJudge(string $raw): array
    {
        $s = trim($raw);
        if (str_starts_with($s, '```')) {
            $s = preg_replace('/^```(json)?\s*/i', '', $s);
            $s = preg_replace('/\s*```$/', '', $s);
        }
        $i = strpos($s, '{'); $j = strrpos($s, '}');
        if ($i !== false && $j !== false && $j > $i) $s = substr($s, $i, $j - $i + 1);
        $arr = json_decode($s, true);
        if (!is_array($arr)) throw new RuntimeException('AI Judge: gagal parse JSON output.');

        $out = [];
        foreach (self::AI_DIMENSI as $dim) {
            if (!isset($arr[$dim]['skor'])) continue;
            $skor = max(1, min(4, (int) $arr[$dim]['skor']));
            $out[$dim] = [
                'skor'        => $skor,
                'justifikasi' => trim((string) ($arr[$dim]['justifikasi'] ?? '')),
            ];
        }
        return $out;
    }

    // ───────────────────── Klasifikasi & saran ─────────────────────

    private function labelKlasifikasi(float $skor): array
    {
        if ($skor >= 3.51) return ['label' => 'Unggul',                 'warna' => 'success'];
        if ($skor >= 3.01) return ['label' => 'Baik Sekali',            'warna' => 'info'];
        if ($skor >= 2.51) return ['label' => 'Baik',                   'warna' => 'primary'];
        if ($skor >= 2.01) return ['label' => 'Cukup',                  'warna' => 'warning'];
        return                     ['label' => 'Perlu Banyak Perbaikan', 'warna' => 'danger'];
    }

    private function saran(string $dim, int $skor, array $detail): string
    {
        return match ($dim) {
            'kelengkapan_kategori' => "Baru {$detail['terisi']}/{$detail['total']} kategori terisi. Kategori belum diisi: " . (empty($detail['kosong']) ? '-' : implode(', ', $detail['kosong'])) . ". Gunakan tombol Saran AI untuk percepat input.",
            'kedalaman_analisis'   => "Rata-rata deskripsi {$detail['avg_char']} karakter (target >150). Tambahkan elaborasi konteks & implikasi pada deskripsi entries.",
            'keseimbangan_swot'    => "Peluang: {$detail['peluang']}, Ancaman: {$detail['ancaman']}. Idealnya masing-masing ≥3, seimbang.",
            'kelengkapan_bukti'    => "{$detail['with']}/{$detail['total']} entri ({$detail['pct']}%) punya link bukti. Lengkapi link bukti agar dapat ditarik otomatis ke PPEPP.",
            'respons_prodi'        => "{$detail['with']}/{$detail['total']} entri {$detail['fokus']} punya respons prodi ({$detail['pct']}%). Pastikan respons konkret & dapat ditindaklanjuti.",
            'sintesis_led'         => 'Narasi LED tersusun untuk: ' . (implode(', ', array_keys(array_filter($detail))) ?: '-') . '. Generate sub-bagian yang belum lewat tombol Draft AI di halaman Narasi LED.',
            default => 'Tingkatkan dimensi ini.',
        };
    }
}
