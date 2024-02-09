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
            'livestockId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmerId' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'causeOfDeath' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'dateOfDeath' => [
                'type' => 'DATE',
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockMortalities');
    }

    public function down()
    {
        $this->forge->dropTable('livestockMortalities');
    }
}
