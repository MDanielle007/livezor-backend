<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivestockBreedSeeder extends Seeder
{
    public function run()
    {
        $livestockBreeds = [
            // Chicken Breeds
            [
                'livestock_breed_name' => 'Native Chicken',
                'livestock_breed_description' => 'Native dual purpose chicken breed in Philippines',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Rhode Island Red',
                'livestock_breed_description' => 'Dual purpose chicken breed imported from US',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Leghorn',
                'livestock_breed_description' => 'Egg laying chicken breed imported from Italy',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Cow Breeds
            [
                'livestock_breed_name' => 'Holstein Friesian',
                'livestock_breed_description' => 'High milk producing dairy cow imported from US and Europe',
                'livestock_type_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Brahman',
                'livestock_breed_description' => 'Beef cattle breed adapted to tropics from India',
                'livestock_type_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Carabao Breeds
            [
                'livestock_breed_name' => 'Philippine Native',
                'livestock_breed_description' => 'Native Philippine carabao breed used for plowing and hauling',
                'livestock_type_id' => '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Pig Breeds
            [
                'livestock_breed_name' => 'Duroc',
                'livestock_breed_description' => 'Lean meat pig breed from US',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Landrace',
                'livestock_breed_description' => 'White pig breed from Denmark used for pork',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Berk shire',
                'livestock_breed_description' => 'Black pig breed known for quality pork',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Goat Breeds
            [
                'livestock_breed_name' => 'Anglo-Nubian',
                'livestock_breed_description' => 'Dual purpose goat breed from UK, used for meat and milk',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Boer',
                'livestock_breed_description' => 'Meat goat breed from South Africa',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Saanen',
                'livestock_breed_description' => 'High milk producing dairy goat breed from Switzerland',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Sheep Breeds
            [
                'livestock_breed_name' => 'Merino',
                'livestock_breed_description' => 'Merino sheep are known for their fine wool, commonly used in clothing production.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Dorper',
                'livestock_breed_description' => 'Dorper sheep are a meat breed known for their hardiness and adaptability to various climates.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'livestock_breed_name' => 'Suffolk',
                'livestock_breed_description' => 'Suffolk sheep are a popular meat breed with good carcass traits and high growth rates.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestock_breeds')->insertBatch($livestockBreeds);
    }
}
