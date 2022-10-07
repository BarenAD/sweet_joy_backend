<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterAdminActionsTableToPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('admin_actions','permissions');
        DB::statement("DELETE from permissions");
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropUnique('admin_actions_name_unique');
            $table->string('permission')->nullable(false)->unique();
        });
        DB::statement("ALTER TABLE permissions comment 'Права для пользователей'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('permissions','admin_actions');
        Schema::table('admin_actions', function (Blueprint $table) {
            $table->dropUnique('permissions_permission_unique');
            $table->dropColumn('permission');
            $table->unique('name');
        });
    }
}
