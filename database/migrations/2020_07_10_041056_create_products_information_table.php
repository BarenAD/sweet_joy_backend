<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_information', function (Blueprint $table) {
            $table->id();
            $table->integer('price');
            $table->integer('count');
            $table->unsignedBigInteger('id_i');
            $table->unsignedBigInteger('id_pos');
            $table->foreign('id_i')->references('id')->on('items');
            $table->foreign('id_pos')->references('id')->on('points_of_sale');
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
        Schema::dropIfExists('products_information');
    }
}
