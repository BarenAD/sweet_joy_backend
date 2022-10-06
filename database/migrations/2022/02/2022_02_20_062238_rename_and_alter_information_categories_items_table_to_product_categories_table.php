<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterInformationCategoriesItemsTableToProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('information_categories_items','product_categories');
        Schema::table('product_categories', function(Blueprint $table) {
            $table->dropForeign('information_categories_items_id_i_foreign');
            $table->dropIndex('information_categories_items_id_i_foreign');
            $table->dropForeign('information_categories_items_id_ci_foreign');
            $table->dropIndex('information_categories_items_id_ci_foreign');
            $table->renameColumn('id_i', 'product_id');
            $table->renameColumn('id_ci', 'category_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE product_categories comment 'Категории на товарах'");
        DB::beginTransaction();
            $products = \App\Models\Product::all();
            foreach ($products as $product) {
                $explodesImage = explode("/", $product->image);
                $product->image = end($explodesImage);
                $product->save();
            }
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('product_categories','information_categories_items');
        Schema::table('information_categories_items', function(Blueprint $table) {
            $table->dropForeign('product_categories_category_id_foreign');
            $table->dropIndex('product_categories_category_id_foreign');
            $table->dropForeign('product_categories_product_id_foreign');
            $table->dropIndex('product_categories_product_id_foreign');
            $table->renameColumn('product_id', 'id_i');
            $table->renameColumn('category_id', 'id_ci');
            $table->foreign('id_i')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('id_ci')->references('id')->on('categories')->onDelete('cascade');
        });
    }
}
