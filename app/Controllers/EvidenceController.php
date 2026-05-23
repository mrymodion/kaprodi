<?php

namespace App\Controllers;

/**
 * Penyaji berkas bukti PDF generik (untuk borang & tridharma).
 */
class EvidenceController extends BaseController
{
    public function lihat(string $name)
    {
        $name = basename($name);
        $path = WRITEPATH . 'bukti/' . $name;
        if (!is_file($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berkas bukti tidak ditemukan.');
        }
        return $this->response->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $name . '"')
            ->setBody(file_get_contents($path));
    }
}
