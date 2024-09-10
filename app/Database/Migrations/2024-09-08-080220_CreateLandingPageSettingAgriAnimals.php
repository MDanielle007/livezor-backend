<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPageSettingAgriAnimals extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'animal' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'count' => [
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
        $this->forge->createTable('landingpage_agri_animals');
    }

    public function down()
    {
        $this->forge->dropTable('landingpage_agri_animals');
    }
}
