<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFarmInformationTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'auto_increment' => true
            ],
            'farmer_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'farm_uid' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique'     => true,
            ],
            'farm_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'sitio' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'barangay' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'province' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'total_area' => [
                'type'       => 'FLOAT',
                'null'       => true,
            ],
            'total_area_unit' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'farm_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'latitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,16',
                'null'       => true,
            ],
            'longitude' => [
                'type'       => 'DECIMAL',
                'constraint' => '20,16',
                'null'       => true,
            ],
            'date_established' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'contact_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true,
            ],
            'owner_type' => [
                'type'       => 'ENUM',
                'constraint' => ['Owned', 'Rented'],
                'null'       => true,
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

        // Define the primary key
        $this->forge->addKey('id', true);

        // Add foreign key constraint
        $this->forge->addForeignKey('farmer_id', 'user_accounts', 'id', 'CASCADE', 'CASCADE');

        // Create the table
        $this->forge->createTable('farm_information');
    }

    public function down()
    {
        $this->forge->dropTable('farm_information');
    }
}
