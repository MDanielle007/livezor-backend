<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockBreedingsTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestockId', 'livestocks', 'id');
        $this->forge->processIndexes('livestockBreedings');
    }

    public function down()
    {
        //
    }
}
