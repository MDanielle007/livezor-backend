<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEggBatchTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'record_owner' => [
                'type' => 'ENUM',
                'constraint' => ['Farmer', 'DA'],
                'default' => 'DA',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'batch_name'=>[
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'batch_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active', 'Inactive'],
                'default' => 'Active',
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
        $this->forge->createTable('egg_production_batch_group');
    }

    public function down()
    {
        $this->forge->dropTable('egg_production_batch_group');
    }
}
