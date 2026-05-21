<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Base model untuk semua model aplikasi.
 * Menyediakan findOrFail() yang melempar PageNotFoundException jika record tidak ditemukan.
 */
class AppModel extends Model
{
    /**
     * Ambil record berdasarkan primary key; lempar 404 jika tidak ada.
     */
    public function findOrFail(int $id): array
    {
        $row = $this->find($id);
        if (!$row) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException(
                get_class($this) . ": record #{$id} tidak ditemukan."
            );
        }
        return $row;
    }
}
