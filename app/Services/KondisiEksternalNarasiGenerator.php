<?php

namespace App\Services;

use App\Models\KondisiEksternalModel;
use App\Models\KondisiEksternalNarasiModel;
use App\Services\Ai\GroqClient;
use RuntimeException;

/**
 * Sprint 3 Kriteria A: AI-assisted draft narasi LED Bab A Kondisi
 * Eksternal. Mengubah data terstruktur 10 kategori menjadi paragraf
 * akademik yang mengalir per sub-bagian (A.1 PESTEL, A.2 Pasar,
 * A.3 SWOT-Ext, A.4 Ringkasan Strategis).
 */
class KondisiEksternalNarasiGenerator
{
    public const SUB_BAGIAN = [
        'A.1' => [
            'judul'    => 'Analisis PESTEL',
            'kategori' => ['politik','ekonomi','sosial','teknologi','hukum'],
            'fokus'    => 'Sintesis 5 dimensi PESTEL menjadi narasi yang menunjukkan keterkaitan antar dimensi serta implikasinya bagi penyelenggaraan prodi.',
        ],
        'A.2' => [
            'judul'    => 'Analisis Pasar & Kompetisi',
            'kategori' => ['demografi','pasar_kerja','kompetitor'],
            'fokus'    => 'Bahas demografi calon mahasiswa, kebutuhan industri/pasar kerja, dan posisi prodi terhadap kompetitor sejenis.',
        ],
        'A.3' => [
            'judul'    => 'Peluang dan Ancaman (SWOT-Eksternal)',
            'kategori' => ['peluang','ancaman'],
            'fokus'    => 'Petakan peluang yang dapat dimanfaatkan dan ancaman yang harus diantisipasi prodi, sebagai dasar penyusunan strategi.',
        ],
        'A.4' => [
            'judul'    => 'Ringkasan Strategis',
            'kategori' => [], // sintesis dari A.1-A.3
            'fokus'    => 'Sintesis A.1-A.3 menjadi 3-5 implikasi strategis untuk pengembangan prodi periode mendatang.',
        ],
    ];

    public function isAiReady(): bool
    {
        return (new GroqClient())->isConfigured();
    }

    /**
     * Generate draft narasi via AI untuk satu sub-bagian.
     * @throws RuntimeException kalau Groq belum dikonfigurasi atau data kosong.
     * @return array{narasi:string, source_count:int}
     */
    public function draft(int $prodiId, string $sub): array
    {
        if (!isset(self::SUB_BAGIAN[$sub])) {
            throw new RuntimeException("Sub-bagian {$sub} tidak dikenali.");
        }
        $ai = new GroqClient();
        if (!$ai->isConfigured()) {
            throw new RuntimeException('GROQ_API_KEY belum di-set di .env — Sprint 3 (AI draft) tidak dapat berjalan.');
        }

        $info = self::SUB_BAGIAN[$sub];
        $context = $this->buildContext($prodiId, $sub, $info);
        $sourceCount = $context['_source_count'] ?? 0;
        unset($context['_source_count']);

        if ($sourceCount === 0) {
            throw new RuntimeException("Belum ada data untuk sub-bagian {$sub}. Isi entri Kondisi Eksternal terlebih dahulu" . ($sub === 'A.4' ? ' dan draft A.1-A.3 sebelum men-draft Ringkasan Strategis.' : '.'));
        }

        $prompt = $this->buildPrompt($sub, $info);
        $narasi = $ai->generateLed($prompt, $context);
        return [
            'narasi'       => trim($narasi),
            'source_count' => $sourceCount,
        ];
    }

    private function buildContext(int $prodiId, string $sub, array $info): array
    {
        $K = KondisiEksternalModel::KATEGORI;
        $D = KondisiEksternalModel::DAMPAK_LABELS;

        if ($sub === 'A.4') {
            // Sintesis: pakai narasi A.1-A.3 yang sudah disimpan + ringkasan kuantitatif.
            $narasiPrev = (new KondisiEksternalNarasiModel())->byProdi($prodiId);
            $ctx = ['narasi_sebelumnya' => []];
            foreach (['A.1','A.2','A.3'] as $s) {
                if (!empty($narasiPrev[$s]['narasi'])) {
                    $ctx['narasi_sebelumnya'][$s] = [
                        'judul'  => self::SUB_BAGIAN[$s]['judul'],
                        'narasi' => $narasiPrev[$s]['narasi'],
                    ];
                }
            }
            // Statistik singkat peluang vs ancaman.
            $cnt = (new KondisiEksternalModel())->countByProdi($prodiId);
            $ctx['statistik'] = [
                'jumlah_peluang' => $cnt['peluang'] ?? 0,
                'jumlah_ancaman' => $cnt['ancaman'] ?? 0,
                'total_faktor'   => array_sum($cnt),
            ];
            $ctx['_source_count'] = count($ctx['narasi_sebelumnya']);
            return $ctx;
        }

        // A.1-A.3: kumpulkan entries dari kategori terkait.
        $rows = (new KondisiEksternalModel())
            ->where('prodi_id', $prodiId)
            ->whereIn('kategori', $info['kategori'])
            ->orderBy('kategori, urutan, id')->findAll();

        $byKat = [];
        foreach ($info['kategori'] as $k) $byKat[$K[$k]['label']] = [];
        foreach ($rows as $r) {
            $byKat[$K[$r['kategori']]['label']][] = [
                'judul'           => $r['judul'],
                'deskripsi'       => $r['deskripsi'],
                'dampak'          => $D[$r['dampak']]['label'],
                'respons_prodi'   => $r['respons_prodi'],
                'sumber_referensi'=> $r['sumber_referensi'],
                'tahun'           => $r['tahun_relevansi'],
            ];
        }
        return [
            'kategori'      => $byKat,
            '_source_count' => count($rows),
        ];
    }

    private function buildPrompt(string $sub, array $info): string
    {
        $base = "Tugas: Susun narasi sub-bagian **{$sub} {$info['judul']}** untuk Bab \"A. Kondisi Eksternal\" pada Laporan Evaluasi Diri (LED) Program Studi.\n\n"
            . "Fokus narasi: {$info['fokus']}\n\n"
            . "Persyaratan WAJIB:\n"
            . "1. Bahasa Indonesia akademik formal, paragraf mengalir (JANGAN bullet/numbered list).\n"
            . "2. Panjang 200-400 kata untuk sub-bagian ini.\n"
            . "3. Pertahankan FAKTA pada data konteks — JANGAN tambah informasi yang tidak ada di data masukan.\n"
            . "4. Sebutkan sumber referensi dalam tanda kurung bila tersedia, mis. (BPS 2024).\n"
            . "5. Awali dengan satu kalimat pengantar yang menempatkan konteks.\n"
            . "6. Akhiri dengan kalimat penutup yang menyebut implikasi atau respons prodi.\n"
            . "7. JANGAN gunakan heading/subheading di dalam narasi; output hanya body paragraf.\n";

        if ($sub === 'A.4') {
            $base .= "\nKhusus A.4: integrasikan poin-poin penting dari narasi A.1-A.3 (tersedia di data konteks `narasi_sebelumnya`) "
                  . "menjadi 3-5 implikasi strategis konkret untuk pengembangan prodi 3-5 tahun ke depan. "
                  . "JANGAN salin-tempel narasi sebelumnya — sintesis ulang menjadi paragraf strategis tingkat tinggi.\n";
        }
        return $base;
    }
}
