<?php

namespace Modules\Akreditasi\Contracts;

/**
 * Kontrak untuk driver instrumen akreditasi.
 * Setiap kombinasi lembaga + jenjang + versi instrumen
 * harus punya satu kelas driver yang mengimplementasi ini.
 */
interface AccreditationDriver
{
    /** Identifier driver (cocok dengan kolom lam_instrumen.driver_class). */
    public function code(): string;

    /** Nama display instrumen (mis. "LAM INFOKOM 2.0 D3"). */
    public function name(): string;

    /**
     * Hitung skor auto untuk semua butir di instrumen ini
     * berdasarkan data aktual prodi & tahun akademik (TS).
     *
     * @return array<string, float> kode_butir => skor (1.00 - 4.00)
     */
    public function computeScores(int $prodiId, ?int $tahunAkademikId = null): array;

    /**
     * Generate baris-baris isi tabel LKPS untuk kode tabel tertentu.
     * @return array<int, array<string, mixed>>
     */
    public function buildLkpsTable(string $kodeTabel, int $prodiId, ?int $tahunAkademikId = null): array;

    /**
     * Aturan validasi sebelum submit (return list pelanggaran, empty = OK).
     * @return string[]
     */
    public function validateSubmission(int $prodiId): array;
}
