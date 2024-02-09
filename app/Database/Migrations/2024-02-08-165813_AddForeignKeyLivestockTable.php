<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        $this->forge->addForeignKey('livestockBreedId', 'livestockBreeds', 'id');
        $this->forge->addForeignKey('livestockAgeClassId', 'livestockAgeClass', 'id');
        $this->forge->processIndexes('livestocks');
    }

    public function down()
    {
        //
    }
}
