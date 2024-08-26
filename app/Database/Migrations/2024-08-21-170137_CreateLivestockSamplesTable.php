<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockSamplesTable extends Migration
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
            'animal_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sample_type' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'sample_description' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'animal_observation' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
            ],
            'findings' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'sample_date' => [
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
        $this->forge->createTable('animal_samples');
    }

    public function down()
    {
        $this->forge->dropTable('animal_samples');
    }
}
