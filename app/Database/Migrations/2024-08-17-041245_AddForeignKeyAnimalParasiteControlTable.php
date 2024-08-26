<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyAnimalParasiteControlTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('animal_parasite_controls');
    }

    public function down()
    {
        //
    }
}
