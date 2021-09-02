<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('identify')->unique();
            $table->unsignedBigInteger('id_d')->nullable();
            $table->foreign('id_d')->references('id')->on('documents')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations_documents');
    }
}
