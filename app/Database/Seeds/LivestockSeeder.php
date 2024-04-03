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
                'breeding_eligibility' => 'Not Age-Suited',
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
                'breeding_eligibility' => 'Not Age-Suited',
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
                'breeding_eligibility' => 'Not Age-Suited',
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Dead',
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
                'breeding_eligibility' => 'Not Age-Suited',
                'date_of_birth' => '2024-03-07',
                'livestock_health_status' => 'Dead',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '5',
                'livestock_tag_id' => 'PG-1001',
                'livestock_type_id' => '4',
                'livestock_breed_id' => '7',
                'livestock_age_class_id' => '21',
                'sex' => 'Male',
                'age_days' => '1687',
                'age_weeks' => '241',
                'age_months' => '56',
                'age_years' => '4',
                'breeding_eligibility' => 'Age-Suited',
                'date_of_birth' => '2019-08-06',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '6',
                'livestock_tag_id' => 'PG-1002',
                'livestock_type_id' => '4',
                'livestock_breed_id' => '7',
                'livestock_age_class_id' => '22',
                'sex' => 'Female',
                'age_days' => '862',
                'age_weeks' => '123',
                'age_months' => '28',
                'age_years' => '2',
                'breeding_eligibility' => 'Age-Suited',
                'date_of_birth' => '2021-11-08',
                'livestock_health_status' => 'Alive',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestocks')->insertBatch($livestock);

    }
}
