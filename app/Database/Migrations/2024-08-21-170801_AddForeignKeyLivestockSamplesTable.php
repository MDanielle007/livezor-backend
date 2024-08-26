<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockSamplesTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('user_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('animal_id', 'livestocks', 'id');
        $this->forge->processIndexes('animal_samples');
    }

    public function down()
    {
        //
    }
}
