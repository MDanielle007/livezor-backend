<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyTargetLivestockProductionTable extends Migration
{
    public function up()
    {
        
        $this->forge->addForeignKey('livestock_type_id', 'livestock_types', 'id');
        $this->forge->processIndexes('target_livestock_production');
    }

    public function down()
    {
        //
    }
}
