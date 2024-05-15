<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePersonnelDetailsTable extends Migration
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
            'position_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'department_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'employee_status' => [
                'type' => 'ENUM',
                'constraint' => ['Active','Inactive','Retired','On Leave'],
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
        $this->forge->createTable('personnel_details');
    }

    public function down()
    {
        $this->forge->dropTable('personnel_details');
    }
}
