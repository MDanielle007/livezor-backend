<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockTypeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestock_type_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'livestock_type_uses' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
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
        $this->forge->createTable('livestock_types');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_types');
    }
}
