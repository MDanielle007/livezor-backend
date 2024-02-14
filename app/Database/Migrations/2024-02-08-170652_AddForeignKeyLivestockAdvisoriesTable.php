<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAdvisoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('target_farmer_id', 'user_accounts', 'id');
        $this->forge->processIndexes('livestock_advisories');
    }

    public function down()
    {
        //
    }
}
