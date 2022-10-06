<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterProductInformationTableToShopsAssortmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('products_information','shop_assortment');
        Schema::table('shop_assortment', function(Blueprint $table) {
            $table->dropForeign('products_information_id_i_foreign');
            $table->dropForeign('products_information_id_pos_foreign');
            $table->dropIndex('products_information_id_i_foreign');
            $table->dropIndex('products_information_id_pos_foreign');
            $table->renameColumn('id_i', 'product_id');
            $table->renameColumn('id_pos', 'shop_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE shop_assortment comment 'Ассортимент продуктов в магазине'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('shop_assortment','products_information');
        Schema::table('products_information', function(Blueprint $table) {
            $table->dropForeign('shop_assortment_product_id_foreign');
            $table->dropForeign('shop_assortment_shop_id_foreign');
            $table->dropIndex('shop_assortment_product_id_foreign');
            $table->dropIndex('shop_assortment_shop_id_foreign');
            $table->renameColumn('product_id', 'id_i');
            $table->renameColumn('shop_id', 'id_pos');
            $table->foreign('id_i')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('id_pos')->references('id')->on('shops')->onDelete('cascade');
        });
    }
}
