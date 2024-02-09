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
            'livestockId' => [
                'type' => 'Varchar',
                'constraint' => 11,
                'unique' => true,
            ],
            'livestockTagId' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
                'null' => true, // Allow NULL values
            ],
            'livestockTypeId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'livestockBreedId' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true, // Allow NULL values
            ],
            'livestockAgeClassId' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'ageDays' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'ageWeeks' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'ageMonths' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'ageYears' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'sex' => [
                'type' => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'breedingEligibility' => [
                'type' => 'ENUM',
                'constraint' => ['Age-Suited', 'Not Age-Suited'],
                'default' => 'Not Age-Suited',
            ],
            'dateOfBirth' => [
                'type' => 'DATE',
            ],
            'livestockHealthStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Alive', 'Sick' ,'Dead'],
                'default' => 'Alive',
            ],
            'recordStatus' => [
                'type' => 'ENUM',
                'constraint' => ['Accessible', 'Archived'],
                'default' => 'Accessible',
            ],
        ]);
        $this->forge->addKey('id', true);
        // $this->forge->addForeignKey('livestockTypeId', 'livestockTypes', 'id');
        // $this->forge->addForeignKey('livestockBreedId', 'livestockBreeds', 'id');
        // $this->forge->addForeignKey('livestockAgeClassId', 'livestockAgeClass', 'id');
        $this->forge->createTable('livestocks');
    }

    public function down()
    {
        $this->forge->dropTable('livestocks');
    }
}
