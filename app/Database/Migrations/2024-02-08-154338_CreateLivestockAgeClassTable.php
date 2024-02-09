<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockAgeClassTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestockAgeClassification' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'ageClassRange' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
            'livestockTypeId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockAgeClass');
    }

    public function down()
    {
        $this->forge->dropTable('livestockAgeClass');
    }
}
