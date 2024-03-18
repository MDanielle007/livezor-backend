<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FarmerLivestockSeeder extends Seeder
{
    public function run()
    {
        $farmerLivestock = [
            [
                'id' => '1',
                'farmer_id' => '2',
                'livestock_id' => '1',
                'acquired_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'farmer_id' => '2',
                'livestock_id' => '2',
                'acquired_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'farmer_id' => '2',
                'livestock_id' => '3',
                'acquired_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'farmer_id' => '2',
                'livestock_id' => '4',
                'acquired_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('farmer_livestocks')->insertBatch($farmerLivestock);
    }
}
