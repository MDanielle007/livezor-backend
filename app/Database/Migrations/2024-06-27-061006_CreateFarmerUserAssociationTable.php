<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFarmerUserAssociationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmer_association_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmer_id' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'position' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'join_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
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
        $this->forge->createTable('farmer_user_associations');
    }

    public function down()
    {
        $this->forge->dropTable('farmer_user_associations');
    }
}
