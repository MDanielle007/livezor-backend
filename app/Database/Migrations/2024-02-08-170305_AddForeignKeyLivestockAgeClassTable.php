<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAgeClassTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        $this->forge->processIndexes('livestockAgeClass');
    }

    public function down()
    {
        //
    }
}
