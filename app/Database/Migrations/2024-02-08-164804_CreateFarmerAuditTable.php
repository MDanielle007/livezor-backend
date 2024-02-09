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
            'livestockId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'farmerId' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
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
            'entityAffected' => [
                'type' => 'ENUM',
                'constraint' => ['Livestock', 'Vaccination', 'Breeding', 'Mortality'],
                'null' => false,
            ],
            'timestamp' => [
                'type' => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('farmerAudit');
    }

    public function down()
    {
        //
    }
}
