<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivestockAgeClassificationSeeder extends Seeder
{
    public function run()
    {
        $livestockAgeClasses = [
            // Chicken 
            [
                'livestock_age_classification' => 'Chick',
                'age_class_range' => '0-4 weeks',
                'age_min_days' => 0,
                'age_max_days' => 28, // 4 weeks = 28 days
                'is_offspring' => true,
                'livestock_type_id'=> '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Pullet',
                'age_class_range' => '2-4 months',
                'age_min_days' => 60, // 2 months = 60 days
                'age_max_days' => 120, // 4 months = 120 days
                'is_offspring' => false,
                'livestock_type_id'=> '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Cockerel',
                'age_class_range' => '3-6 months',
                'age_min_days' => 90, // 3 months = 90 days
                'age_max_days' => 180, // 6 months = 180 days
                'is_offspring' => false,
                'livestock_type_id'=> '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Rooster',
                'age_class_range' => '6 months and above',
                'age_min_days' => 180, // 6 months = 180 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Hen',
                'age_class_range' => '6 months and above',
                'age_min_days' => 180, // 6 months = 180 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Cow 
            [
                'livestock_age_classification' => 'Calf',
                'age_class_range' => '0-6 months',
                'age_min_days' => 0,
                'age_max_days' => 180, // 6 months = 180 days
                'is_offspring' => true,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Yearling',
                'age_class_range' => '6-12 months',
                'age_min_days' => 180, // 6 months = 180 days
                'age_max_days' => 365, // 1 year = 365 days
                'is_offspring' => false,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Heifer',
                'age_class_range' => '1-3 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 1095, // 3 years = 1095 days
                'is_offspring' => false,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Steer',
                'age_class_range' => '1-3 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 1095, // 3 years = 1095 days
                'is_offspring' => false,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Cow',
                'age_class_range' => '3 years and above',
                'age_min_days' => 1095, // 3 years = 1095 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Bull',
                'age_class_range' => '3 years and above',
                'age_min_days' => 1095, // 3 years = 1095 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '2',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Carabao 
            [
                'livestock_age_classification' => 'Calf',
                'age_class_range' => '0-1 year',
                'age_min_days' => 0,
                'age_max_days' => 365, // 1 year = 365 days
                'is_offspring' => true,
                'livestock_type_id'=> '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Heifer',
                'age_class_range' => '1-3 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 1095, // 3 years = 1095 days
                'is_offspring' => false,
                'livestock_type_id'=> '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Bull',
                'age_class_range' => '1-3 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 1095, // 3 years = 1095 days
                'is_offspring' => false,
                'livestock_type_id'=> '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Female',
                'age_class_range' => '3 years and above',
                'age_min_days' => 1095, // 3 years = 1095 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Male',
                'age_class_range' => '3 years and above',
                'age_min_days' => 1095, // 3 years = 1095 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '3',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Pig 
            [
                'livestock_age_classification' => 'Piglet',
                'age_class_range' => '0-6 weeks',
                'age_min_days' => 0,
                'age_max_days' => 42, // 6 weeks = 42 days
                'is_offspring' => true,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Weaner',
                'age_class_range' => '6 weeks - 3 months',
                'age_min_days' => 42, // 6 weeks = 42 days
                'age_max_days' => 90, // 3 months = 90 days
                'is_offspring' => false,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Grower',
                'age_class_range' => '3-6 months',
                'age_min_days' => 90, // 3 months = 90 days
                'age_max_days' => 180, // 6 months = 180 days
                'is_offspring' => false,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Finisher',
                'age_class_range' => '6 - 8 months',
                'age_min_days' => 180, // 6 months = 180 days
                'age_max_days' => 240, // 8 months = 240 days
                'is_offspring' => false,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Sow',
                'age_class_range' => '8-18 months and above',
                'age_min_days' => 240, // 8 months = 240 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Boar',
                'age_class_range' => '8-18 months and above',
                'age_min_days' => 240, // 8 months = 240 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '4',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Goat
            [
                'livestock_age_classification' => 'Kid',
                'age_class_range' => '0-12 months',
                'age_min_days' => 0,
                'age_max_days' => 365, // 12 months = 365 days
                'is_offspring' => true,
                'livestock_type_id'=> '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Yearling',
                'age_class_range' => '1-2 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 730, 
                'is_offspring' => false,
                'livestock_type_id'=> '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Mature Goat',
                'age_class_range' => '2-4 years',
                'age_min_days' => 730, 
                'age_max_days' => 1460, // 4 years = 1460 days
                'is_offspring' => false,
                'livestock_type_id'=> '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Goat',
                'age_class_range' => '4 years and above',
                'age_min_days' => 1460, // 4 years = 1460 days
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '5',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Sheep
            [
                'livestock_age_classification' => 'Lamb',
                'age_class_range' => '0-6 months',
                'age_min_days' => 0,
                'age_max_days' => 180, // 6 months = 180 days
                'is_offspring' => true,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Yearling',
                'age_class_range' => '6-12 months',
                'age_min_days' => 180, // 6 months = 180 days
                'age_max_days' => 365, // 12 months = 365 days
                'is_offspring' => false,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Ewe Lamb',
                'age_class_range' => '1-2 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 730, 
                'is_offspring' => false,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Wether Lamb',
                'age_class_range' => '1-2 years',
                'age_min_days' => 365, // 1 year = 365 days
                'age_max_days' => 730, 
                'is_offspring' => false,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Ewe',
                'age_class_range' => '2 years and above',
                'age_min_days' => 730, 
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Ram',
                'age_class_range' => '2 years and above',
                'age_min_days' => 730, 
                'age_max_days' => null, // No maximum age limit
                'is_offspring' => false,
                'livestock_type_id'=> '6',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Duck
            [
                'livestock_age_classification' => 'Ducklings',
                'age_class_range' => '0 - 7 days',
                'age_min_days' => 0, 
                'age_max_days' => 7, 
                'is_offspring' => true,
                'livestock_type_id'=> '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Grower/Juveniles',
                'age_class_range' => '8 days - 20 weeks',
                'age_min_days' => 8, 
                'age_max_days' => 140, 
                'is_offspring' => false,
                'livestock_type_id'=> '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Young Adults',
                'age_class_range' => '21 Weeks - 32 weeks',
                'age_min_days' => 147, 
                'age_max_days' => 224, 
                'is_offspring' => false,
                'livestock_type_id'=> '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Ducks',
                'age_class_range' => '33 weeks and above',
                'age_min_days' => 231, 
                'age_max_days' => null, 
                'is_offspring' => false,
                'livestock_type_id'=> '7',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Turkey
            [
                'livestock_age_classification' => 'Turkey Poults',
                'age_class_range' => '0 - 4 weeks',
                'age_min_days' => 0, 
                'age_max_days' => 28, 
                'is_offspring' => true,
                'livestock_type_id'=> '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Growers/Juveniles',
                'age_class_range' => '5 - 16 weeks',
                'age_min_days' => 29, 
                'age_max_days' => 112, 
                'is_offspring' => false,
                'livestock_type_id'=> '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Young Adults',
                'age_class_range' => '17 - 28 weeks',
                'age_min_days' => 113, 
                'age_max_days' => 196, 
                'is_offspring' => false,
                'livestock_type_id'=> '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Turkeys',
                'age_class_range' => '29 weeks and above',
                'age_min_days' => 195, 
                'age_max_days' => null, 
                'is_offspring' => false,
                'livestock_type_id'=> '8',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Goose
            [
                'livestock_age_classification' => 'Goslings',
                'age_class_range' => '0 - 4 weeks',
                'age_min_days' => 0, 
                'age_max_days' => 28, 
                'is_offspring' => true,
                'livestock_type_id'=> '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Juveniles',
                'age_class_range' => '5 - 20 weeks',
                'age_min_days' => 29, 
                'age_max_days' => 140, 
                'is_offspring' => false,
                'livestock_type_id'=> '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Young Adults',
                'age_class_range' => '21 - 32 weeks',
                'age_min_days' => 147, 
                'age_max_days' => 224, 
                'is_offspring' => false,
                'livestock_type_id'=> '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Goose',
                'age_class_range' => '33 weeks and above',
                'age_min_days' => 225, 
                'age_max_days' => null, 
                'is_offspring' => false,
                'livestock_type_id'=> '9',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],

            // Quail
            [
                'livestock_age_classification' => 'Chicks/Hatchlings',
                'age_class_range' => '0 - 1 weeks',
                'age_min_days' => 0, 
                'age_max_days' => 7, 
                'is_offspring' => true,
                'livestock_type_id'=> '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Juveniles',
                'age_class_range' => '2 - 5 weeks',
                'age_min_days' => 8, 
                'age_max_days' => 35, 
                'is_offspring' => false,
                'livestock_type_id'=> '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Young Adult',
                'age_class_range' => '6 - 8 weeks',
                'age_min_days' => 36, 
                'age_max_days' => 56, 
                'is_offspring' => false,
                'livestock_type_id'=> '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'livestock_age_classification' => 'Adult Quail',
                'age_class_range' => '9 weeks and above',
                'age_min_days' => 57, 
                'age_max_days' => null, 
                'is_offspring' => false,
                'livestock_type_id'=> '10',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        $this->db->table('livestock_age_class')->insertBatch($livestockAgeClasses);
    }
}
