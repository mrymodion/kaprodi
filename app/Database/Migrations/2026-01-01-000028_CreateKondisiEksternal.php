<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Sprint 2 Kriteria A: input terstruktur Kondisi Eksternal sebagai
 * pengganti narasi LED bebas. Mendukung kerangka PESTEL + SWOT-eksternal
 * (peluang/ancaman) + demografi + pasar kerja + kompetitor.
 */
class CreateKondisiEksternal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'         => ['type' => 'INT', 'unsigned' => true],
            'kategori'         => ['type' => 'ENUM', 'constraint' => [
                'politik','ekonomi','sosial','teknologi','hukum',
                'demografi','pasar_kerja','kompetitor','peluang','ancaman'
            ]],
            'judul'            => ['type' => 'VARCHAR', 'constraint' => 250],
            'deskripsi'        => ['type' => 'TEXT'],
            'dampak'           => ['type' => 'ENUM', 'constraint' => ['rendah','sedang','tinggi'], 'default' => 'sedang'],
            'respons_prodi'    => ['type' => 'TEXT', 'null' => true],
            'sumber_referensi' => ['type' => 'VARCHAR', 'constraint' => 250, 'null' => true],
            'link_bukti'       => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
            'tahun_relevansi'  => ['type' => 'YEAR', 'null' => true],
            'urutan'           => ['type' => 'INT', 'default' => 0],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['prodi_id', 'kategori']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kondisi_eksternal');
    }

    public function down()
    {
        $this->forge->dropTable('kondisi_eksternal', true);
    }
}
