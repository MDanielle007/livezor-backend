<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MortalitySeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'livestock_id' => '1',
                'farmer_id' => '2',
                'cause_of_death' => 'Pneumonia',
                'mortality_remarks' => '',
                'date_of_death' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'livestock_id' => '2',
                'farmer_id' => '2',
                'cause_of_death' => 'Heatstroke',
                'mortality_remarks' => '',
                'date_of_death' => date('Y-m-d'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestock_mortalities')->insertBatch($data);
    }
}
