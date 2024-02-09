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
            'livestockBreedName' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'livestockBreedDescription' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'livestockId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockBreedings');
    }

    public function down()
    {
        $this->forge->dropTable('livestockBreedings');
    }
}
