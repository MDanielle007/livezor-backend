<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockOffspringTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'pregnancy_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'sex' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
                'null' => false,
            ],
            'offspring_notes' => [
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
        $this->forge->createTable('livestock_offspring');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_offspring');
    }
}
