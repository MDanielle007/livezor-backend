<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivestockTypeSeeder extends Seeder
{
    public function run()
    {
        $livestockTypes = [
            [
                'id'=> '1',
                'livestock_type_name' => 'Chicken',
                'livestock_type_uses' => 'Meat, Eggs',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '2',
                'livestock_type_name' => 'Cattle',
                'livestock_type_uses' => 'Meat, Milk',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '3',
                'livestock_type_name' => 'Carabao',
                'livestock_type_uses' => 'Meat, Labor',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '4',
                'livestock_type_name' => 'Pig',
                'livestock_type_uses' => 'Meat',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '5',
                'livestock_type_name' => 'Goat',
                'livestock_type_uses' => 'Meat, Milk',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'=> '6',
                'livestock_type_name' => 'Sheep',
                'livestock_type_uses' => 'Meat, Wool',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            // Add more livestock types as needed
        ];

        $this->db->table('livestock_types')->insertBatch($livestockTypes);
    }
}
