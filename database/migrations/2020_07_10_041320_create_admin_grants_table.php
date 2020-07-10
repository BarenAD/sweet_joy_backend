<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminGrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_grants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ar');
            $table->unsignedBigInteger('id_aa');
            $table->foreign('id_ar')->references('id')->on('admin_roles');
            $table->foreign('id_aa')->references('id')->on('admin_actions');
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
        Schema::dropIfExists('admin_grants');
    }
}
