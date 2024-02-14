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
            'vaccine_administrator_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
            'vaccination_date' => [
                'type' => 'DATE',
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
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
