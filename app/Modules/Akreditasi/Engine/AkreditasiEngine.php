<?php

namespace Modules\Akreditasi\Engine;

use Config\Database;
use Modules\Akreditasi\Contracts\AccreditationDriver;

/**
 * Facade untuk operasi penilaian akreditasi per prodi.
 * Mengabstraksikan pemanggilan driver, penyimpanan skor, dan
 * kalkulasi total per kriteria / keseluruhan.
 */
class AkreditasiEngine
{
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /** Ambil driver yang aktif untuk prodi. */
    public function getDriver(int $prodiId): ?AccreditationDriver
    {
        $row = $this->db->query("
            SELECT li.driver_class
            FROM prodi_instrumen pi
            JOIN lam_instrumen li ON li.id = pi.lam_instrumen_id
            WHERE pi.prodi_id = ? AND pi.aktif = 1
            ORDER BY pi.id DESC LIMIT 1
        ", [$prodiId])->getRowArray();

        if (!$row || !$row['driver_class']) return null;
        $class = $row['driver_class'];
        return class_exists($class) ? new $class() : null;
    }

    /** Ambil instrumen aktif prodi. */
    public function getInstrumen(int $prodiId): ?array
    {
        return $this->db->query("
            SELECT li.*, ll.nama AS nama_lembaga
            FROM prodi_instrumen pi
            JOIN lam_instrumen li ON li.id = pi.lam_instrumen_id
            JOIN lam_lembaga ll ON ll.id = li.lam_lembaga_id
            WHERE pi.prodi_id = ? AND pi.aktif = 1
            ORDER BY pi.id DESC LIMIT 1
        ", [$prodiId])->getRowArray();
    }

    /** Semua kriteria + butir + skor untuk prodi, dikelompokkan. */
    public function getKriteriaWithButir(int $prodiId, int $instrumenId, ?int $taId): array
    {
        $kriteria = $this->db->query("
            SELECT * FROM lam_kriteria
            WHERE lam_instrumen_id = ?
            ORDER BY urutan
        ", [$instrumenId])->getResultArray();

        $butirRows = $this->db->query("
            SELECT b.*,
                bs.skor_auto, bs.skor_manual, bs.skor_final, bs.catatan
            FROM lam_butir b
            LEFT JOIN butir_skor bs ON bs.lam_butir_id = b.id
                AND bs.prodi_id = ?
                AND (bs.tahun_akademik_id = ? OR (bs.tahun_akademik_id IS NULL AND ? IS NULL))
            WHERE b.lam_kriteria_id IN (
                SELECT id FROM lam_kriteria WHERE lam_instrumen_id = ?
            )
            ORDER BY b.urutan
        ", [$prodiId, $taId, $taId, $instrumenId])->getResultArray();

        // Index butir by kriteria_id
        $byKriteria = [];
        foreach ($butirRows as $b) {
            $byKriteria[$b['lam_kriteria_id']][] = $b;
        }

        foreach ($kriteria as &$k) {
            $k['butir'] = $byKriteria[$k['id']] ?? [];

            // Hitung skor rata tertimbang per kriteria
            $totalBobot = array_sum(array_column($k['butir'], 'bobot'));
            $skorSum    = 0;
            $skorCount  = 0;
            foreach ($k['butir'] as $b) {
                $skor = $b['skor_final'] ?? $b['skor_auto'] ?? null;
                if ($skor !== null) {
                    $skorSum += (float)$skor * (float)$b['bobot'];
                    $skorCount += (float)$b['bobot'];
                }
            }
            $k['skor_rata']   = $skorCount > 0 ? round($skorSum / $skorCount, 2) : null;
            $k['total_bobot'] = $totalBobot;
            $k['jml_butir']   = count($k['butir']);
            $k['jml_isi']     = count(array_filter($k['butir'], fn($b) => ($b['skor_final'] ?? $b['skor_auto']) !== null));
        }

        return $kriteria;
    }

    /** Jalankan driver untuk menyimpan skor auto ke butir_skor. */
    public function runAutoScore(int $prodiId, ?int $taId): int
    {
        $driver = $this->getDriver($prodiId);
        if (!$driver) return 0;

        $scores = $driver->computeScores($prodiId, $taId);
        $count  = 0;

        foreach ($scores as $kodeButir => $skor) {
            $butir = $this->db->query("
                SELECT b.id FROM lam_butir b
                JOIN lam_kriteria k ON k.id = b.lam_kriteria_id
                JOIN lam_instrumen li ON li.id = k.lam_instrumen_id
                JOIN prodi_instrumen pi ON pi.lam_instrumen_id = li.id
                WHERE b.kode = ? AND pi.prodi_id = ?
            ", [$kodeButir, $prodiId])->getRowArray();

            if (!$butir) continue;

            $existing = $this->db->query("
                SELECT id, skor_manual FROM butir_skor
                WHERE prodi_id = ? AND lam_butir_id = ? AND (tahun_akademik_id = ? OR (tahun_akademik_id IS NULL AND ? IS NULL))
            ", [$prodiId, $butir['id'], $taId, $taId])->getRowArray();

            $skorFinal = $existing['skor_manual'] ?? $skor;

            if ($existing) {
                $this->db->query("
                    UPDATE butir_skor SET skor_auto=?, skor_final=?, updated_at=NOW()
                    WHERE id=?
                ", [$skor, $skorFinal, $existing['id']]);
            } else {
                $this->db->query("
                    INSERT INTO butir_skor (prodi_id, lam_butir_id, tahun_akademik_id, skor_auto, skor_final, updated_at)
                    VALUES (?,?,?,?,?,NOW())
                ", [$prodiId, $butir['id'], $taId, $skor, $skorFinal]);
            }
            $count++;
        }
        return $count;
    }

    /** Simpan skor manual satu butir. */
    public function saveSkorManual(int $prodiId, int $butirId, ?int $taId, ?float $skor, ?string $catatan, int $userId): void
    {
        $existing = $this->db->query("
            SELECT id, skor_auto FROM butir_skor
            WHERE prodi_id=? AND lam_butir_id=? AND (tahun_akademik_id=? OR (tahun_akademik_id IS NULL AND ? IS NULL))
        ", [$prodiId, $butirId, $taId, $taId])->getRowArray();

        $skorFinal = $skor ?? ($existing['skor_auto'] ?? null);

        if ($existing) {
            $this->db->query("
                UPDATE butir_skor SET skor_manual=?, skor_final=?, catatan=?, updated_at=NOW(), updated_by=?
                WHERE id=?
            ", [$skor, $skorFinal, $catatan, $userId, $existing['id']]);
        } else {
            $this->db->query("
                INSERT INTO butir_skor (prodi_id,lam_butir_id,tahun_akademik_id,skor_manual,skor_final,catatan,updated_at,updated_by)
                VALUES (?,?,?,?,?,?,NOW(),?)
            ", [$prodiId, $butirId, $taId, $skor, $skorFinal, $catatan, $userId]);
        }
    }

    /** Hitung total skor keseluruhan instrumen (nilai akhir dari 400). */
    public function totalSkor(int $prodiId, int $instrumenId, ?int $taId): array
    {
        $rows = $this->db->query("
            SELECT k.kode, k.bobot AS bobot_kriteria, b.bobot AS bobot_butir,
                   COALESCE(bs.skor_final, bs.skor_auto) AS skor
            FROM lam_kriteria k
            JOIN lam_butir b ON b.lam_kriteria_id = k.id
            LEFT JOIN butir_skor bs ON bs.lam_butir_id = b.id AND bs.prodi_id = ?
                AND (bs.tahun_akademik_id = ? OR (bs.tahun_akademik_id IS NULL AND ? IS NULL))
            WHERE k.lam_instrumen_id = ?
        ", [$prodiId, $taId, $taId, $instrumenId])->getResultArray();

        $kriteriaBobot = [];
        $kriteriaScore = [];
        $kriteriaMax   = [];

        foreach ($rows as $r) {
            $kode = $r['kode'];
            $kriteriaBobot[$kode] = (float)$r['bobot_kriteria'];
            if ($r['skor'] !== null) {
                $kriteriaScore[$kode] = ($kriteriaScore[$kode] ?? 0) + (float)$r['skor'] * (float)$r['bobot_butir'];
                $kriteriaMax[$kode]   = ($kriteriaMax[$kode] ?? 0) + 4 * (float)$r['bobot_butir'];
            }
        }

        // Nilai akhir = sum( (rata_skor_kriteria / 4) * bobot_kriteria )
        $nilaiAkhir = 0;
        foreach ($kriteriaBobot as $kode => $bobot) {
            if (isset($kriteriaScore[$kode]) && $kriteriaMax[$kode] > 0) {
                $nilaiAkhir += ($kriteriaScore[$kode] / $kriteriaMax[$kode]) * $bobot;
            }
        }

        return [
            'nilai_akhir' => round($nilaiAkhir, 2),
            'max_nilai'   => array_sum($kriteriaBobot),
            'pct'         => array_sum($kriteriaBobot) > 0 ? round($nilaiAkhir / array_sum($kriteriaBobot) * 100, 1) : 0,
            'predikat'    => $this->predikat($nilaiAkhir, array_sum($kriteriaBobot)),
        ];
    }

    private function predikat(float $nilai, float $max): string
    {
        if ($max === 0) return '-';
        $pct = $nilai / $max * 100;
        if ($pct >= 85)  return 'Unggul';
        if ($pct >= 70)  return 'Baik Sekali';
        if ($pct >= 55)  return 'Baik';
        if ($pct >= 40)  return 'Cukup';
        return 'Kurang';
    }
}
