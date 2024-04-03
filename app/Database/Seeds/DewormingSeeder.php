<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DewormingSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'dewormer_id' => '2',
                'livestock_id' => '1',
                'dosage' => '10 ml',
                'administration_method' => 'Oral',
                'deworming_remarks' => 'Livestock appeared healthy after deworming.',
                'next_deworming_date' => '2024-06-15',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'dewormer_id' => '2',
                'livestock_id' => '2',
                'dosage' => '10 ml',
                'administration_method' => 'Oral',
                'deworming_remarks' => 'Livestock appeared healthy after deworming.',
                'next_deworming_date' => '2024-06-15',
                'deworming_date' => '2024-03-15',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'dewormer_id' => '2',
                'livestock_id' => '4',
                'dosage' => '15 ml',
                'administration_method' => 'Injectable',
                'deworming_remarks' => 'Livestock showed signs of discomfort during administration.',
                'next_deworming_date' => '2024-07-20',
                'deworming_date' => '2024-03-20',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'dewormer_id' => '2',
                'livestock_id' => '1',
                'dosage' => '15 ml',
                'administration_method' => 'Injectable',
                'deworming_remarks' => 'Livestock showed signs of discomfort during administration.',
                'next_deworming_date' => '2024-07-20',
                'deworming_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '5',
                'dewormer_id' => '2',
                'livestock_id' => '2',
                'dosage' => '10 ml',
                'administration_method' => 'Oral',
                'deworming_remarks' => 'Livestock appeared healthy after deworming.',
                'next_deworming_date' => '2024-06-15',
                'deworming_date' => '2024-03-15',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestock_dewormings')->insertBatch($data);
    }
}
