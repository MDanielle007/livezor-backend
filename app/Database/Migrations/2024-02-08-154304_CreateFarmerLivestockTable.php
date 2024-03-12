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
            'farmer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'acquired_date' => [
                'type' => 'DATE',
            ],
            'ownership_status' => [
                'type' => 'ENUM',
                'constraint' => ['Owned', 'Sold', 'Deceased', 'Transferred'],
                'default' => 'Owned',
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archive'],
                'default' => 'Accessible',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('farmer_livestocks');
    }

    public function down()
    {
        $this->forge->dropTable('farmer_livestocks');
    }
}
