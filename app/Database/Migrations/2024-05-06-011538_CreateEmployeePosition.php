<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmployeePosition extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'position_name' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'department_id' => [
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
        $this->forge->createTable('personnel_positions');
    }

    public function down()
    {
        $this->forge->dropTable('personnel_positions');
    }
}
