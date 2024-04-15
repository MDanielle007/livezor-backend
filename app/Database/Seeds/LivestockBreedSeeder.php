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
                'id' => '1',
                'livestock_breed_name' => 'Native Chicken',
                'livestock_breed_description' => 'Native dual purpose chicken breed in Philippines',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'livestock_breed_name' => 'Rhode Island Red',
                'livestock_breed_description' => 'Dual purpose chicken breed imported from US',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'livestock_breed_name' => 'Leghorn',
                'livestock_breed_description' => 'Egg laying chicken breed imported from Italy',
                'livestock_type_id' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Cow Breeds
            [
                'id' => '4',
                'livestock_breed_name' => 'Holstein Friesian',
                'livestock_breed_description' => 'High milk producing dairy cow imported from US and Europe',
                'livestock_type_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '5',
                'livestock_breed_name' => 'Brahman',
                'livestock_breed_description' => 'Beef cattle breed adapted to tropics from India',
                'livestock_type_id' => '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Carabao Breeds
            [
                'id' => '6',
                'livestock_breed_name' => 'Philippine Native',
                'livestock_breed_description' => 'Native Philippine carabao breed used for plowing and hauling',
                'livestock_type_id' => '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Pig Breeds
            [
                'id' => '7',
                'livestock_breed_name' => 'Duroc',
                'livestock_breed_description' => 'Lean meat pig breed from US',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '8',
                'livestock_breed_name' => 'Landrace',
                'livestock_breed_description' => 'White pig breed from Denmark used for pork',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '9',
                'livestock_breed_name' => 'Berk shire',
                'livestock_breed_description' => 'Black pig breed known for quality pork',
                'livestock_type_id' => '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Goat Breeds
            [
                'id' => '10',
                'livestock_breed_name' => 'Anglo-Nubian',
                'livestock_breed_description' => 'Dual purpose goat breed from UK, used for meat and milk',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '11',
                'livestock_breed_name' => 'Boer',
                'livestock_breed_description' => 'Meat goat breed from South Africa',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '12',
                'livestock_breed_name' => 'Saanen',
                'livestock_breed_description' => 'High milk producing dairy goat breed from Switzerland',
                'livestock_type_id' => '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Sheep Breeds
            [
                'id' => '13',
                'livestock_breed_name' => 'Merino',
                'livestock_breed_description' => 'Merino sheep are known for their fine wool, commonly used in clothing production.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '14',
                'livestock_breed_name' => 'Dorper',
                'livestock_breed_description' => 'Dorper sheep are a meat breed known for their hardiness and adaptability to various climates.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '15',
                'livestock_breed_name' => 'Suffolk',
                'livestock_breed_description' => 'Suffolk sheep are a popular meat breed with good carcass traits and high growth rates.',
                'livestock_type_id' => '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Duck Breeds
            [
                'id' => '16',
                'livestock_breed_name' => 'Pateros Duck',
                'livestock_breed_description' => 'The Pateros duck, also known as Itik Pateros, is a native Philippine duck breed.',
                'livestock_type_id' => '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '17',
                'livestock_breed_name' => 'Peking Duck',
                'livestock_breed_description' => 'Peking ducks are a popular meat breed raised for their tender and flavorful meat.',
                'livestock_type_id' => '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '18',
                'livestock_breed_name' => 'Khaki Campbell Duck',
                'livestock_breed_description' => 'Khaki Campbell ducks are renowned for their high egg production, making them popular among commercial egg producers.',
                'livestock_type_id' => '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],


            // Turkey Breeds
            [
                'id' => '19',
                'livestock_breed_name' => 'Broad Breasted White Turkey',
                'livestock_breed_description' => 'The Broad Breasted White is one of the most common commercial turkey breeds raised for meat production worldwide.',
                'livestock_type_id' => '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '20',
                'livestock_breed_name' => 'Bronze Turkey',
                'livestock_breed_description' => 'Bronze turkeys are a heritage breed known for their rich flavor and traditional appearance.',
                'livestock_type_id' => '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '21',
                'livestock_breed_name' => 'Narragansett Turkey',
                'livestock_breed_description' => 'They are another heritage breed with a unique appearance characterized by slate-colored plumage with white markings.',
                'livestock_type_id' => '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Goose Breeds
            [
                'id' => '22',
                'livestock_breed_name' => 'Embden Goose',
                'livestock_breed_description' => 'The Embden goose is one of the most common and popular meat breeds of geese worldwide.',
                'livestock_type_id' => '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '23',
                'livestock_breed_name' => 'Pilgrim Goose',
                'livestock_breed_description' => 'Pilgrim geese are a heritage breed known for their calm temperament and dual-purpose characteristics.',
                'livestock_type_id' => '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],

            // Quail Breeds
            [
                'id' => '24',
                'livestock_breed_name' => 'Japanese Quail',
                'livestock_breed_description' => 'They are small-sized birds with mottled brown plumage, speckled chests, and distinctive white stripes above their eyes.',
                'livestock_type_id' => '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '25',
                'livestock_breed_name' => 'Bobwhite Quail',
                'livestock_breed_description' => 'They have a more subdued appearance compared to Japanese quail, with brown and buff-colored plumage and a distinctive white throat patch.',
                'livestock_type_id' => '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('livestock_breeds')->insertBatch($livestockBreeds);
    }
}
