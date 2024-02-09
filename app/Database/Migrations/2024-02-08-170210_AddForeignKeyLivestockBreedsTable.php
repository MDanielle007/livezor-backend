<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockBreedsTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        $this->forge->processIndexes('livestockBreeds');
    }

    public function down()
    {
        //
    }
}
