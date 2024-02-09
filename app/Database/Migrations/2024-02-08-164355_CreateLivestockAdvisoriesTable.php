<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockAdvisoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'content' => [
                'type' => 'TEXT',
            ],
            'targetFarmerId' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'isGeneral' => [
                'type' => 'BOOL',
            ],
            'datePublished' => [
                'type' => 'DATE',
            ],
            'isRead' => [
                'type' => 'BOOL',
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('livestockAdvisories');
    }

    public function down()
    {
        //
    }
}
