<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DiskonSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        $tanggal_awal = date('Y-m-d');
        $created_at = date('Y-m-d H:i:s');
        $nominals = [100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000, 1000000];

        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'tanggal' => date('Y-m-d', strtotime("+$i day", strtotime($tanggal_awal))),
                'nominal' => $nominals[$i],
                'created_at' => $created_at,
                'updated_at' => null,
            ];
        }

        $this->db->table('diskon')->insertBatch($data);
    }
} 