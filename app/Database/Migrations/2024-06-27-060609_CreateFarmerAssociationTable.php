<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFarmerAssociationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'farmer_association_name' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'overview' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'sitio' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'barangay' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'city' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'province' => [
                'type' => 'TEXT',
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
        $this->forge->createTable('farmer_associations');
    }

    public function down()
    {
        $this->forge->dropTable('farmer_associations');
    }
}
