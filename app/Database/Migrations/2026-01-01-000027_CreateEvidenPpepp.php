<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Eviden PPEPP (Penetapan-Pelaksanaan-Evaluasi-Pengendalian-Peningkatan)
 * per butir penilaian LAM, dipakai sebagai bahan visitasi asesor.
 */
class CreateEvidenPpepp extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'prodi_id'     => ['type' => 'INT', 'unsigned' => true],
            'lam_butir_id' => ['type' => 'INT', 'unsigned' => true],
            'penetapan'    => ['type' => 'TEXT', 'null' => true],
            'pelaksanaan'  => ['type' => 'TEXT', 'null' => true],
            'evaluasi'     => ['type' => 'TEXT', 'null' => true],
            'pengendalian' => ['type' => 'TEXT', 'null' => true],
            'peningkatan'  => ['type' => 'TEXT', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_by'   => ['type' => 'INT', 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey(['prodi_id', 'lam_butir_id']);
        $this->forge->addForeignKey('prodi_id', 'prodi', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('lam_butir_id', 'lam_butir', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('eviden_ppepp');

        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'eviden_id'  => ['type' => 'INT', 'unsigned' => true],
            'fase'       => ['type' => 'ENUM', 'constraint' => ['penetapan','pelaksanaan','evaluasi','pengendalian','peningkatan']],
            'label'      => ['type' => 'VARCHAR', 'constraint' => 250],
            'url'        => ['type' => 'TEXT'],
            'sumber'     => ['type' => 'VARCHAR', 'constraint' => 40, 'default' => 'manual'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addKey(['eviden_id', 'fase']);
        $this->forge->addForeignKey('eviden_id', 'eviden_ppepp', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('eviden_ppepp_bukti');
    }

    public function down()
    {
        $this->forge->dropTable('eviden_ppepp_bukti', true);
        $this->forge->dropTable('eviden_ppepp', true);
    }
}
