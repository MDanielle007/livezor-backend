<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAdvisoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('targetFarmerId', 'userAccounts', 'id');
        $this->forge->processIndexes('livestockAdvisories');
    }

    public function down()
    {
        //
    }
}
