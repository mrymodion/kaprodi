<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('MasterSeeder');
        $this->call('LamInfokomD3Seeder');
        // AuthDemoSeeder hanya jalan jika Shield sudah ter-install (cek di dalam seeder).
        $this->call('AuthDemoSeeder');
    }
}
