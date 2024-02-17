<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockBreedingTable extends Migration
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
            'livestock_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'male_livestock_tag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => true, // Allow NULL values
            ],
            'female_livestock_tag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => true, // Allow NULL values
            ],
            'breed_result' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'breed_additional_notes' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'breed_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestock_breedings');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_breedings');
    }
}
