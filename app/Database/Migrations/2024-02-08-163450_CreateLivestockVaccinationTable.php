<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockVaccinationTable extends Migration
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
            'administrator_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'vaccination_name' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
            ],
            'vaccination_description' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'vaccination_remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'vaccination_date' => [
                'type' => 'DATE',
            ],
            'vaccination_location' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
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
        $this->forge->createTable('livestock_vaccinations');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_vaccinations');
    }
}
