<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLandingPageMainImageCarousel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'image_filename' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'order_num' => [
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
        $this->forge->createTable('landingpage_main_display_images');
    }

    public function down()
    {
        $this->forge->dropTable('landingpage_main_display_images');
    }
}
