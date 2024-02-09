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
            'vaccineAdministratorId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestockId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'vaccinationName' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
            ],
            'vaccinationDescription' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'vaccinationDate' => [
                'type' => 'DATE',
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockVaccinations');
    }

    public function down()
    {
        $this->forge->dropTable('livestockVaccinations');
    }
}
