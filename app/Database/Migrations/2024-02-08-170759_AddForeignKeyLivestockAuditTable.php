<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAuditTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('farmer_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('livestock_id', 'livestocks', 'id');
        $this->forge->processIndexes('farmer_audit');
    }

    public function down()
    {
        //
    }
}
