<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEggProductionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'farmer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'eggs_produced' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'additional_egg_prod_notes' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
            ],
            'date_of_production' => [
                'type' => 'DATE',
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
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
        $this->forge->createTable('livestock_egg_productions');
    }

    public function down()
    {
        $this->forge->dropTable('livestock_egg_productions');
    }
}
