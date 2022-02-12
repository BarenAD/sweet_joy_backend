<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformationCategoriesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('information_categories_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_i');
            $table->unsignedBigInteger('id_ci');
            $table->foreign('id_i')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('id_ci')->references('id')->on('categories_item')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('information_categories_items');
    }
}
