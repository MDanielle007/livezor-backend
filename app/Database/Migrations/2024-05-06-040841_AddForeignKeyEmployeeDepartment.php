<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyEmployeeDepartment extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('user_id', 'user_accounts', 'id');
        $this->forge->addForeignKey('position_id', 'personnel_positions','id');
        $this->forge->addForeignKey('department_id', 'personnel_departments','id');
        $this->forge->processIndexes('personnel_details');
    }

    public function down()
    {
        //
    }
}
