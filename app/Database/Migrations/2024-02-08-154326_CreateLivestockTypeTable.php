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
            'livestockTypeName' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'livestockTypeUses' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockTypes');
    }

    public function down()
    {
        $this->forge->dropTable('livestockTypes');
    }
}
