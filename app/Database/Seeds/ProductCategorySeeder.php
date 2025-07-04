<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Laptop',
                'description' => 'Berbagai jenis laptop untuk kebutuhan kerja dan gaming.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Aksesoris Komputer',
                'description' => 'Mouse, keyboard, headset, dan aksesoris lainnya.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Komponen PC',
                'description' => 'Processor, RAM, VGA, motherboard, dan komponen lainnya.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Software',
                'description' => 'Sistem operasi, aplikasi produktivitas, dan software lainnya.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

         foreach ($data as $item) {
            // insert semua data ke tabel
            $this->db->table('product_category')->insert($item);
        }
    }
}
