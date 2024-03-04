<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockPregnancyTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'breeding_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'outcome' => [
                'type' => 'ENUM',
                'constraint' => ['Successful', 'Miscarriage', 'Pending'],
                'null' => false,
                'default' => 'Pending'
            ],
            'pregnancy_start_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'expected_delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'actual_delivery_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'pregnancy_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestock_pregnancies');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_pregnancies');
    }
}
