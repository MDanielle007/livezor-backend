<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEmployeePositions extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('department_id', 'personnel_departments','id');
        $this->forge->processIndexes('personnel_positions');
    }

    public function down()
    {
        //
    }
}
