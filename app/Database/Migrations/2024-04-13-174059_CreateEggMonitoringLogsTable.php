<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEggMonitoringLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
            ],
            'record_owner' => [
                'type' => 'ENUM',
                'constraint' => ['Farmer', 'DA'],
                'default' => 'DA',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'egg_process_batch_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'action'=>[
                'type' => 'ENUM',
                'constraint' => ['Setting', 'Checking', 'Extracting'],
            ],
            'date_conducted' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true, // Allow NULL values
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
        $this->forge->createTable('egg_monitoring_logs');
    }

    public function down()
    {
        $this->forge->dropTable('egg_monitoring_logs');
    }
}
