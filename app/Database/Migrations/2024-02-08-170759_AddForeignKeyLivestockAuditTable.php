<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyLivestockAuditTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('farmerId', 'userAccounts', 'id');
        $this->forge->addForeignKey('livestockId', 'livestocks', 'id');
        $this->forge->processIndexes('farmerAudit');
    }

    public function down()
    {
        //
    }
}
