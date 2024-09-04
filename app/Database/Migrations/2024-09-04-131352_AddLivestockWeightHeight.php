<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLivestockWeightHeight extends Migration
{
    public function up()
    {
        $fields = [
            'height' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'height_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'weight' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'weight_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
        ];
        $this->forge->addColumn('livestocks', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('livestocks', ['height','height_unit','weight','weight_unit']); // to drop one single column
    }
}
