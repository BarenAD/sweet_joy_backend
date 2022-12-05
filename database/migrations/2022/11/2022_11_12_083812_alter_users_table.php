<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_login_unique');
            $table->dropColumn('login');
        });
        DB::statement("ALTER TABLE users comment 'Пользователи'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login');
        });
        Schema::table('users', function (Blueprint $table) {
            User::query()->chunk(100, function ($users) {
                foreach ($users as $user) {
                    DB::statement('UPDATE users SET login="'.uniqid().'" WHERE id='.$user->id);
                }
            });
            $table->unique('login');
        });
    }
}
