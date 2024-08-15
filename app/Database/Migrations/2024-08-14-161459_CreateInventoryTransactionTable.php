<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTransactionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'transaction_type' => [
                'type' => 'ENUM',
                'constraint' => ['Restock', 'Distribution', 'Adjustment'],
            ],
            'quantity' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0
            ],
            'transaction_remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'transaction_date' => [
                'type' => 'DATE',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->forge->createTable('inventory_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('inventory_transactions');
    }
}
