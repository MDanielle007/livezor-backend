<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryItemTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'item_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'unit_of_measurement' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'reorder_level' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'item_image' => [
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
        $this->forge->createTable('inventory_items');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_items');
    }
}
