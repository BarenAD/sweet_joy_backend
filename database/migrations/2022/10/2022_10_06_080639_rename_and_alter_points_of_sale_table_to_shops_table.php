<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterPointsOfSaleTableToShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('points_of_sale','shops');
        Schema::table('shops', function(Blueprint $table) {
            $table->dropForeign('points_of_sale_id_s_foreign');
            $table->dropIndex('points_of_sale_id_s_foreign');
            $table->renameColumn('id_s', 'schedule_id');
            $table->foreign('schedule_id')->references('id')->on('schedules');
        });
        DB::statement("ALTER TABLE shops comment 'Точки продаж (магазины)'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('shops','points_of_sale');
        Schema::table('points_of_sale', function(Blueprint $table) {
            $table->dropForeign('shops_schedule_id_foreign');
            $table->dropIndex('shops_schedule_id_foreign');
            $table->renameColumn('schedule_id', 'id_s');
            $table->foreign('id_s')->references('id')->on('schedules');
        });
    }
}
