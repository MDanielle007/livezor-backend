<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LandingPageSettingSeeder extends Seeder
{
    public function run()
    {
        $this->call('LandingPageSettingMainDisplayText');
    }
}
