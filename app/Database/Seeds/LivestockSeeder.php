<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivestockSeeder extends Seeder
{
    public function run()
    {
        $livestock = [
            [
                'id' => '1',
                'livestock_tag_id' => 'CRB-1001',
                'livestock_type_id' => '3',
                'livestock_breed_id' => '6',
                'livestock_age_class_id' => '12',
                'sex' => 'Male',
                'age_days' => 10,
                'age_weeks' => 1,
                'age_months' => 0,
                'age_years' => 0,
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'livestock_tag_id' => 'CRB-1002',
                'livestock_type_id' => '3',
                'livestock_breed_id' => '6',
                'livestock_age_class_id' => '12',
                'sex' => 'Female',
                'age_days' => 10,
                'age_weeks' => '1',
                'age_months' => '0',
                'age_years' => '0',
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'livestock_tag_id' => 'CRB-1003',
                'livestock_type_id' => '3',
                'livestock_breed_id' => '6',
                'livestock_age_class_id' => '12',
                'sex' => 'Female',
                'age_days' => 10,
                'age_weeks' => '1',
                'age_months' => '0',
                'age_years' => '0',
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'livestock_tag_id' => 'CRB-1004',
                'livestock_type_id' => '3',
                'livestock_breed_id' => '6',
                'livestock_age_class_id' => '12',
                'sex' => 'Male',
                'age_days' => '10',
                'age_weeks' => '1',
                'age_months' => '0',
                'age_years' => '0',
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestocks')->insertBatch($livestock);

    }
}
