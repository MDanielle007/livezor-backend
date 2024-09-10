<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LandingPageSettingOrgChart extends Seeder
{
    public function run()
    {
        $this->db->table('landingpage_org_chart')->insert([
            'name' => 'Components of DA-RIARC and DA-MURTHA',
            'subtitle' => 'Organizational Chart',
            'image' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
