<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyFarmerEggInventoryTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('farmer_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('livestock_type_id', 'livestock_types', 'id');
        $this->forge->processIndexes('farmer_egg_inventory');
    }

    public function down()
    {
        //
    }
}
