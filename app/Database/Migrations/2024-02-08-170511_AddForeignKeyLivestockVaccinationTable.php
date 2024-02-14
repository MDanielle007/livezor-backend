<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockVaccinationTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('livestock_vaccinations');
    }

    public function down()
    {
        //
    }
}
