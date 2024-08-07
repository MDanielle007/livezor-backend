<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEggDistributionTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('poultry_type_id', 'livestock_types', 'id');
        $this->forge->addForeignKey('poultry_breed_id', 'livestock_breeds', 'id');
        $this->forge->processIndexes('egg_distribution');
    }

    public function down()
    {
        //
    }
}
