<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyInventoryItemTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('category_id', 'inventory_items_category','id');
        $this->forge->processIndexes('inventory_items');
    }

    public function down()
    {
        //
    }
}
