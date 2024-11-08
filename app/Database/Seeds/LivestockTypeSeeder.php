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
                'livestock_type_name' => 'Cattle',
                'livestock_type_uses' => 'Meat, Milk',
                'category' => 'Livestock',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '2',
                'livestock_type_name' => 'Sheep',
                'livestock_type_uses' => 'Meat, Wool',
                'category' => 'Livestock',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'=> '3',
                'livestock_type_name' => 'Goat',
                'livestock_type_uses' => 'Meat, Milk',
                'category' => 'Livestock',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'id'=> '4',
                'livestock_type_name' => 'Chicken',
                'livestock_type_uses' => 'Meat, Eggs',
                'category' => 'Poultry',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            // Add more livestock types as needed
        ];

        $this->db->table('livestock_types')->insertBatch($livestockTypes);
    }
}
