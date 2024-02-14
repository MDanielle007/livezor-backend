<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockMortalityTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'cause_of_death' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'date_of_death' => [
                'type' => 'DATE',
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestock_mortalities');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_mortalities');
    }
}
