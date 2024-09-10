<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LandingPageSettingMainDisplayText extends Seeder
{
    public function run()
    {
        $this->db->table('landingpage_display_title')->insert([
            'image_filename' => 'Oriental Mindoro Livestock and Poultry',
            'main_display_subtitle' => 'Integrated Laboratory Division',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
