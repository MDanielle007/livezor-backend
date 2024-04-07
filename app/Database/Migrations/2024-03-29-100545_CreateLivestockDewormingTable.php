<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockDewormingTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'dewormer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dosage' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'administration_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'deworming_remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'next_deworming_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'deworming_date' => [
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
        $this->forge->createTable('livestock_dewormings');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_dewormings');
    }
}
