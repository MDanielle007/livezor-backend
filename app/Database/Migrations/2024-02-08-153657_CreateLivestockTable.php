<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLivestockTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'livestock_tag_id' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => true, // Allow NULL values
            ],
            'livestock_type_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestock_breed_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true, // Allow NULL values
            ],
            'livestock_age_class_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'age_days' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'age_weeks' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'age_months' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'age_years' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'sex' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'breeding_eligibility' => [
                'type' => 'ENUM',
                'constraint' => ['Age-Suited', 'Not Age-Suited'],
                'default' => 'Not Age-Suited',
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'livestock_health_status' => [
                'type' => 'ENUM',
                'constraint' => ['Alive', 'Sick' ,'Dead'],
                'default' => 'Alive',
            ],
            'record_status' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('livestocks');
    }

    public function down()
    {
        $this->forge->dropTable('livestocks');
    }
}
