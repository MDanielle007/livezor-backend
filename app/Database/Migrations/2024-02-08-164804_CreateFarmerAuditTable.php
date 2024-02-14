<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;


class CreateFarmerAuditTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestock_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'action' => [
                'type' => 'ENUM',
                'constraint' => ['Add', 'Edit', 'Delete', 'Archived'],
                'null' => false,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'entity_affected' => [
                'type' => 'ENUM',
                'constraint' => ['Livestock', 'Vaccination', 'Breeding', 'Mortality'],
                'null' => false,
            ],
            'timestamp' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('farmer_audit');
    }

    public function down()
    {
        //
    }
}
