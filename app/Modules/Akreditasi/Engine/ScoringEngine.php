<?php

namespace Modules\Akreditasi\Engine;

use Config\Database;
use Modules\Akreditasi\Contracts\AccreditationDriver;

/**
 * Engine universal: agregasi skor butir -> kriteria -> instrumen.
 * Bekerja untuk driver instrumen apapun selama mengikuti
 * struktur tabel lam_instrumen / lam_kriteria / lam_butir.
 */
class ScoringEngine
{
    public function __construct(private AccreditationDriver $driver, private int $instrumenId) {}

    /**
     * Simpan skor auto driver ke tabel butir_skor.
     * @return int jumlah baris di-upsert.
     */
    public function recompute(int $prodiId, ?int $tahunAkademikId = null): int
    {
        $db = Database::connect();
        $scores = $this->driver->computeScores($prodiId, $tahunAkademikId);
        if (empty($scores)) return 0;

        $butirMap = $db->table('lam_butir b')
            ->select('b.id, b.kode')
            ->join('lam_kriteria k', 'k.id = b.lam_kriteria_id')
            ->where('k.lam_instrumen_id', $this->instrumenId)
            ->get()->getResultArray();
        $kodeToId = array_column($butirMap, 'id', 'kode');

        $count = 0;
        foreach ($scores as $kode => $skor) {
            if (!isset($kodeToId[$kode])) continue;
            $butirId = $kodeToId[$kode];

            $existing = $db->table('butir_skor')
                ->where(['prodi_id' => $prodiId, 'lam_butir_id' => $butirId, 'tahun_akademik_id' => $tahunAkademikId])
                ->get()->getRowArray();

            $row = [
                'prodi_id'          => $prodiId,
                'lam_butir_id'      => $butirId,
                'tahun_akademik_id' => $tahunAkademikId,
                'skor_auto'         => round((float)$skor, 2),
                'skor_final'        => round((float)$skor, 2),
                'updated_at'        => date('Y-m-d H:i:s'),
            ];
            if ($existing) {
                if ($existing['skor_manual'] !== null) {
                    $row['skor_final'] = $existing['skor_manual'];
                }
                $db->table('butir_skor')->where('id', $existing['id'])->update($row);
            } else {
                $db->table('butir_skor')->insert($row);
            }
            $count++;
        }
        return $count;
    }

    /**
     * Agregat skor: butir -> kriteria (weighted average per butir),
     * lalu kriteria -> total (weighted by bobot kriteria).
     * @return array{kriteria: array<string, float>, total: float, persen: float}
     */
    public function summarize(int $prodiId, ?int $tahunAkademikId = null): array
    {
        $db = Database::connect();

        $rows = $db->table('lam_kriteria k')
            ->select('k.kode AS kriteria, k.bobot AS bobot_kriteria, b.id AS butir_id, b.bobot AS bobot_butir, s.skor_final')
            ->join('lam_butir b', 'b.lam_kriteria_id = k.id', 'left')
            ->join('butir_skor s', "s.lam_butir_id = b.id AND s.prodi_id = {$prodiId}" . ($tahunAkademikId ? " AND s.tahun_akademik_id = {$tahunAkademikId}" : ' AND s.tahun_akademik_id IS NULL'), 'left')
            ->where('k.lam_instrumen_id', $this->instrumenId)
            ->orderBy('k.urutan')
            ->get()->getResultArray();

        $byKriteria = [];
        foreach ($rows as $r) {
            $k = $r['kriteria'];
            $byKriteria[$k] ??= ['bobot' => (float)$r['bobot_kriteria'], 'sum_w' => 0, 'sum_ws' => 0];
            if ($r['skor_final'] !== null) {
                $w = (float)$r['bobot_butir'];
                $byKriteria[$k]['sum_w']  += $w;
                $byKriteria[$k]['sum_ws'] += $w * (float)$r['skor_final'];
            }
        }

        $kriteriaScores = [];
        $totalBobot = 0; $totalBobotSkor = 0;
        foreach ($byKriteria as $k => $v) {
            $skor = $v['sum_w'] > 0 ? round($v['sum_ws'] / $v['sum_w'], 2) : 0.0;
            $kriteriaScores[$k] = $skor;
            $totalBobot += $v['bobot'];
            $totalBobotSkor += $v['bobot'] * $skor;
        }
        $total = $totalBobot > 0 ? round($totalBobotSkor / $totalBobot, 2) : 0.0;
        return [
            'kriteria' => $kriteriaScores,
            'total'    => $total,
            'persen'   => round(($total / 4) * 100, 2),
        ];
    }
}
