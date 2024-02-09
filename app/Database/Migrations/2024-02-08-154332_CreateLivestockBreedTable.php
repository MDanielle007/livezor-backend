<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockBreedTable extends Migration
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
            'livestockTypeId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        $this->forge->createTable('livestockBreeds');
    }

    public function down()
    {
        $this->forge->dropTable('livestockBreeds');
    }
}
