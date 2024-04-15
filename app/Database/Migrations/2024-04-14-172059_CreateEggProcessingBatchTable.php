<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEggProcessingBatchTable extends Migration
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
                'null' => true,
            ],
            'egg_batch_group_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'batch_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'machine' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'total_eggs' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'mortalities' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'produced_poultry' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['Processing', 'Finished'],
                'default' => 'Processing',
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
        $this->forge->createTable('egg_processing_batch');
    }

    public function down()
    {
        $this->forge->dropTable('egg_processing_batch');
    }
}
