<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonnelDepartmentsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=> '1',
                'department_name' => 'Research Division',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('personnel_departments')->insertBatch($data);
    }
}
