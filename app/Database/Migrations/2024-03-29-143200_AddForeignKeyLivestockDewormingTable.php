<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockDewormingTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('dewormer_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('livestock_dewormings');
    }

    public function down()
    {
        //
    }
}
