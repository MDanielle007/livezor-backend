<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockBreedingsTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('livestock_breedings');
    }

    public function down()
    {
        //
    }
}
