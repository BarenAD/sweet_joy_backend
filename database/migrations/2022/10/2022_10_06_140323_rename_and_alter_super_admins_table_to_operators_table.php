<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterSuperAdminsTableToOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('super_admins','operators');
        Schema::table('operators', function (Blueprint $table) {
            $table->dropForeign('super_admins_id_u_foreign');
            $table->dropIndex('super_admins_id_u_foreign');
            $table->renameColumn('id_u', 'user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE operators comment 'Высшие администраторы'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('operators','super_admins');
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropForeign('operators_user_id_foreign');
            $table->dropIndex('operators_user_id_foreign');
            $table->renameColumn('user_id', 'id_u');
            $table->foreign('id_u')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
