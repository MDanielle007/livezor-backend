<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyFarmerUserAssociationTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('user_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('farmer_association_id', 'farmer_associations','id');
        $this->forge->processIndexes('farmer_user_associations');
    }

    public function down()
    {
        //
    }
}
