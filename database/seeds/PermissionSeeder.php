<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $nowDate = Carbon::now()->format('Y-m-d H:i:s');
        Permission::insert([
            [
                'id' => 1,
                'name' => '',
                'description' => '',
                'permission' => '',
                'created_at' => $nowDate,
                'updated_at' => $nowDate,
            ],
        ]);
    }
}
