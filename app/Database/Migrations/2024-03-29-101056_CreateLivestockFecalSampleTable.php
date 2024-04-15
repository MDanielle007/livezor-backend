<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockFecalSampleTable extends Migration
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
            'livestock_observation' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
            ],
            'findings' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'fecal_sample_date' => [
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
        $this->forge->createTable('livestock_fecal_samples');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_fecal_samples');
    }
}
