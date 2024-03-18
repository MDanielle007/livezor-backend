<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LivestockBaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('LivestockTypeSeeder');
        $this->call('LivestockBreedSeeder');
        $this->call('LivestockAgeClassificationSeeder');
    }
}
