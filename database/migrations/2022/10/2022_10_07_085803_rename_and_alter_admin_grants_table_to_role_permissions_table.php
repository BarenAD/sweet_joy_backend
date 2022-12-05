<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RenameAndAlterAdminGrantsTableToRolePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('admin_grants','role_permissions');
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropForeign('admin_grants_id_aa_foreign');
            $table->dropForeign('admin_grants_id_ar_foreign');
            $table->dropIndex('admin_grants_id_aa_foreign');
            $table->dropIndex('admin_grants_id_ar_foreign');
            $table->renameColumn('id_aa', 'permission_id');
            $table->renameColumn('id_ar', 'role_id');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
        DB::statement("ALTER TABLE role_permissions comment 'Права пользователей для роли пользователя'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('role_permissions','admin_grants');
        Schema::table('admin_grants', function (Blueprint $table) {
            $table->dropForeign('role_permissions_permission_id_foreign');
            $table->dropForeign('role_permissions_role_id_foreign');
            $table->dropIndex('role_permissions_permission_id_foreign');
            $table->dropIndex('role_permissions_role_id_foreign');
            $table->renameColumn('permission_id', 'id_aa');
            $table->renameColumn('role_id', 'id_ar');
            $table->foreign('id_aa')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('id_ar')->references('id')->on('roles')->onDelete('cascade');
        });
    }
}
