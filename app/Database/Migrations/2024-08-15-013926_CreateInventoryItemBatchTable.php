<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryItemBatchTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'batch_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'inventory_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'initial_quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0
            ],
            'current_quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Expired', 'Depleted', 'Quarantined'],
                'default' => 'Active'
            ],
            'production_date' => [
                'type' => 'DATE',
            ],
            'expiration_date' => [
                'type' => 'DATE',
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
        $this->forge->createTable('inventory_item_batches');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_item_batches');
    }
}
