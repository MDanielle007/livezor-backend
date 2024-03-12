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
            'livestock_breed_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'livestock_breed_description' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'livestock_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        // $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        $this->forge->createTable('livestock_breeds');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_breeds');
    }
}
