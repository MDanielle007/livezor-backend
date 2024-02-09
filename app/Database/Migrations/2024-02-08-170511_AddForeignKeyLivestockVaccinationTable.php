<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockVaccinationTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestockId', 'livestocks', 'id');
        $this->forge->processIndexes('livestockVaccinations');
    }

    public function down()
    {
        //
    }
}
