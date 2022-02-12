<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsOfSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_of_sale', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('phone');
            $table->longText('map_integration')->nullable();
            $table->unsignedBigInteger('id_s');
            $table->foreign('id_s')->references('id')->on('schedules');
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
        Schema::dropIfExists('points_of_sale');
    }
}
