<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins_information', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ar');
            $table->unsignedBigInteger('id_pos');
            $table->unsignedBigInteger('id_u');
            $table->foreign('id_ar')->references('id')->on('admin_roles');
            $table->foreign('id_pos')->references('id')->on('points_of_sale');
            $table->foreign('id_u')->references('id')->on('users');
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
        Schema::dropIfExists('admins_information');
    }
}
