<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LandingPageSettingContactInformation extends Seeder
{
    public function run()
    {
        $livestockTypes = [
            [
                'id'=> '1',
                'setting' => 'email',
                'value' => 'damimaropa.researchdiv@gmail.com',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '2',
                'setting' => 'location',
                'value' => 'Barcenaga, Naujan, Oriental Mindoro',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '3',
                'setting' => 'availbleDays',
                'value' => '["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '4',
                'setting' => 'openHour',
                'value' => '08:00 am',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '5',
                'setting' => 'closedHour',
                'value' => '05:00 pm',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'id'=> '6',
                'setting' => 'locationMap',
                'value' => '<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d3716.348919370848!2d121.24435599999998!3d13.275101!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33bcebe3dce21341%3A0xb351fcfbe2a99b33!2sDA%20MiMaRoPa%20Research%20Division!5e1!3m2!1sen!2sph!4v1723382539138!5m2!1sen!2sph" allowfullscreen=" loading="lazy" width="600" height="450" referrerpolicy="no-referrer-when-downgrade" ></iframe>',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('landingpage_contact_info')->insertBatch($livestockTypes);
    }
}
