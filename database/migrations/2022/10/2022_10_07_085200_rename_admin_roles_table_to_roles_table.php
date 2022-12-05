<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAdminRolesTableToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('admin_roles','roles');
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique('admin_roles_name_unique');
            $table->unique('name');
        });
        DB::statement("ALTER TABLE roles comment 'Роли для пользователей'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('roles','admin_roles');
        Schema::table('admin_roles', function (Blueprint $table) {
            $table->dropUnique('roles_name_unique');
            $table->unique('name');
        });
    }
}
