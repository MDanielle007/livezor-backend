<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class VaccinationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'id' => '1',
                'vaccine_administrator_id' => '2',
                'livestock_id' => '1',
                'vaccination_name' => 'Carabao Fever Vaccine',
                'vaccination_description' => 'This vaccine protects against carabao fever.',
                'vaccination_remarks' => '',
                'vaccination_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '2',
                'vaccine_administrator_id' => '2',
                'livestock_id' => '2',
                'vaccination_name' => 'Hoof and Mouth Disease Vaccine',
                'vaccination_description' => 'This vaccine prevents hoof and mouth disease in carabaos.',
                'vaccination_remarks' => '',
                'vaccination_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '3',
                'vaccine_administrator_id' => '2',
                'livestock_id' => '3',
                'vaccination_name' => 'Rabies Vaccine',
                'vaccination_description' => 'This vaccine protects carabaos against rabies.',
                'vaccination_remarks' => '',
                'vaccination_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => '4',
                'vaccine_administrator_id' => '2',
                'livestock_id' => '4',
                'vaccination_name' => 'Anthrax Vaccine',
                'vaccination_description' => 'This vaccine prevents anthrax infection in carabaos.',
                'vaccination_remarks' => '',
                'vaccination_date' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $this->db->table('livestock_vaccinations')->insertBatch($data);
    }
}
