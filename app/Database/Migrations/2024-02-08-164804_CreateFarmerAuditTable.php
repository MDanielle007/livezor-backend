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
                'null' => true,
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
                'constraint' => ['Livestock', 'Poultry', 'Vaccination', 'Deworming', 'Egg Production','Breeding', 'Mortality', 'Pregnancy', 'Fecal Sample', 'Blood Sample', 'Audit', 'Advisories','Egg Distribution'],
                'null' => false,
            ],
            'timestamp' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => new RawSql ('CURRENT_TIMESTAMP')
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
        $this->forge->createTable('farmer_audit');
    }

    public function down()
    {
        $this->forge->dropTable('farmer_audit');
    }
}
