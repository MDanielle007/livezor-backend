<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEggProductionTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('farmer_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->addForeignKey('batch_group_id', 'egg_production_batch_group','id');
        $this->forge->processIndexes('livestock_egg_productions');
    }

    public function down()
    {
        //
    }
}
