<?php

namespace App\Models;

class LedNarasiModel extends AppModel
{
    protected $table      = 'led_narasi';
    protected $allowedFields = ['prodi_id', 'lam_template_led_id', 'isi', 'generated_by_ai', 'updated_by'];
    protected $useTimestamps = true;

    /** Ambil semua narasi prodi, di-index by lam_template_led_id. */
    public function getByProdi(int $prodiId): array
    {
        $rows = $this->where('prodi_id', $prodiId)->findAll();
        $indexed = [];
        foreach ($rows as $r) {
            $indexed[$r['lam_template_led_id']] = $r;
        }
        return $indexed;
    }

    /** Upsert satu narasi. */
    public function upsert(int $prodiId, int $templateId, string $isi, bool $byAi, int $userId): void
    {
        $existing = $this->where('prodi_id', $prodiId)
                         ->where('lam_template_led_id', $templateId)
                         ->first();
        $data = [
            'prodi_id'            => $prodiId,
            'lam_template_led_id' => $templateId,
            'isi'                 => $isi,
            'generated_by_ai'     => $byAi ? 1 : 0,
            'updated_by'          => $userId,
        ];
        if ($existing) {
            $this->update($existing['id'], $data);
        } else {
            $this->insert($data);
        }
    }
}
