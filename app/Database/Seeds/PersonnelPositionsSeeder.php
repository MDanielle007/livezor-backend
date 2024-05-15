<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PersonnelPositionsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id'=> '1',
                'position_name' => 'OIC, RTD/ Chief',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '2',
                'position_name' => 'Agricultural Center Chief III',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '3',
                'position_name' => 'Agricultural Center Chief II',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '4',
                'position_name' => 'Agricultural Center Chief I',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '5',
                'position_name' => 'APCO Occidental Mindoro',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '6',
                'position_name' => 'Farm Superintendent III',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '7',
                'position_name' => 'Farm Superintendent II',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '8',
                'position_name' => 'Farm Superintendent I',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '9',
                'position_name' => 'Science Research Specialist I',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '10',
                'position_name' => 'Project Manager',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '11',
                'position_name' => 'Technical Staff',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '12',
                'position_name' => 'Farm Worker II',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '13',
                'position_name' => 'Farm Worker I',
                'department_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('personnel_positions')->insertBatch($data);
    }
}
