<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * @throws Exception
     */
    public function run()
    {
        try {
            DB::transaction(function () {
                $this->call(DocumentLocationSeeder::class);
                $this->call(SiteConfigurationSeeder::class);
                $this->call(PermissionSeeder::class);
                $this->call(DemoDBSeeder::class);
                DB::commit();
            });
        } catch (\Exception $error) {
            throw new \Exception($error);
        }
    }
}
