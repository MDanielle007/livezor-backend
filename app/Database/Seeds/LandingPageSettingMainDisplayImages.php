<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LandingPageSettingMainDisplayImages extends Seeder
{
    public function run()
    {
        $this->db->table('landingpage_main_display_images')->insert([
            'image_filename' => '1707916936_d693c47c311735a77b6d.jpg',
            'order_num' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
