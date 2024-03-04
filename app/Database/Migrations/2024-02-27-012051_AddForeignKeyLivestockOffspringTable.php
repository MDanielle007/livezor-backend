<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockOffspringTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('pregnancy_id', 'livestock_pregnancies', 'id');
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('livestock_offspring');
    }

    public function down()
    {
        //
    }
}
