<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddForeignKeyInventoryItemBatchTable extends Migration
{
    public function up()
    {
        $this->forge->addForeignKey('inventory_item_id', 'inventory_items','id');
        $this->forge->processIndexes('inventory_item_batches');
    }

    public function down()
    {
        //
    }
}
