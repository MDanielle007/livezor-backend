<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockPregnancyTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('breeding_id', 'livestock_breedings', 'id');
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('livestock_pregnancies');
    }

    public function down()
    {
        //
    }
}
