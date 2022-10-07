<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterAdminsInformationsTableToUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('admins_information','user_roles');
        Schema::table('user_roles', function (Blueprint $table) {
            $table->dropForeign('admins_information_id_ar_foreign');
            $table->dropForeign('admins_information_id_pos_foreign');
            $table->dropForeign('admins_information_id_u_foreign');
            $table->dropIndex('admins_information_id_ar_foreign');
            $table->dropIndex('admins_information_id_pos_foreign');
            $table->dropIndex('admins_information_id_u_foreign');
            $table->renameColumn('id_ar', 'role_id');
            $table->renameColumn('id_u', 'user_id');
            $table->dropColumn('id_pos');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE user_roles comment 'Роли на пользователях'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('user_roles','admins_information');
        Schema::table('admins_information', function (Blueprint $table) {
            $table->dropForeign('user_roles_role_id_foreign');
            $table->dropForeign('user_roles_user_id_foreign');
            $table->dropIndex('user_roles_role_id_foreign');
            $table->dropIndex('user_roles_user_id_foreign');
            $table->renameColumn('role_id', 'id_ar');
            $table->renameColumn('user_id', 'id_u');
            $table->unsignedBigInteger('id_pos');
            $table->foreign('id_u')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_ar')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('id_pos')->references('id')->on('shops')->onDelete('cascade');
        });
    }
}
