<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductionNativeLivestockTable extends Migration
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
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'produced_livestock' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'number_of_mortality' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'date_of_production' => [
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
        $this->forge->createTable('native_livestock_productions');
    }

    public function down()
    {
        $this->forge->dropTable('native_livestock_productions');
    }
}
