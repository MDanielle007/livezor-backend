<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPageMainDisplayText extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'main_display_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'main_display_subtitle' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->createTable('landingpage_display_title');
    }

    public function down()
    {
        $this->forge->dropTable('landingpage_display_title');
    }
}
