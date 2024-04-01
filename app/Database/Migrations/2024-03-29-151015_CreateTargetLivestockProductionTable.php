<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTargetLivestockProductionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'year' => [
                'type' => 'YEAR',
            ],
            'month' => [
                'type' => 'TINYINT',
                'constraint' => 2,
            ],
            'target_quantity' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'target_measurement_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
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
        
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('target_livestock_production', true);
    }

    public function down()
    {
        $this->forge->dropTable('target_livestock_production');
    }
}
