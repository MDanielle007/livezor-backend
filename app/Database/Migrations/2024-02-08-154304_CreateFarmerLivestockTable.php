<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFarmerLivestockTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'farmerId' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 11,
            ],
            'livestockId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'acquiredDate' => [
                'type' => 'DATE',
            ],
            'ownershipStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Owned', 'Sold', 'Deceased', 'Transferred'],
                'default' => 'Owned',
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archive'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('farmerId', 'userAccounts', 'id');
        // $this->forge->addForeignKey('livestockId', 'livestocks', 'id');
        $this->forge->createTable('farmerlivestocks');
    }

    public function down()
    {
        $this->forge->dropTable('farmerlivestocks');
    }
}
