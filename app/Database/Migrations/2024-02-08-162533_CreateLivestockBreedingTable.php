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
            'livestock_breed_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'livestock_breed_description' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestock_breedings');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_breedings');
    }
}
