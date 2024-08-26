<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnimalParasiteControlTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'farmer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'drug_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'administrator_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'parasite_name' => [
                'type' => 'VARCHAR',
                'constraint' => 70,
                'null' => true,
            ],
            'dosage' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'administration_method' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'next_application_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'application_date' => [
                'type' => 'DATE',
            ],
            'application_location' => [
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
        $this->forge->createTable('animal_parasite_controls');
    }

    public function down()
    {
        $this->forge->dropTable('animal_parasite_controls');
    }
}
