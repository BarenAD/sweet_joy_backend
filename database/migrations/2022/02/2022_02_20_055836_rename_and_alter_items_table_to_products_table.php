<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterItemsTableToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('items','products');
        Schema::table('products', function(Blueprint $table) {
            $table->renameColumn('picture', 'image');
            $table->dropColumn('miniature_picture');
        });
        DB::statement("ALTER TABLE products comment 'Товары'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('products','items');
        Schema::table('items', function(Blueprint $table) {
            $table->renameColumn('image', 'picture');
            $table->string('miniature_picture');
        });
    }
}
