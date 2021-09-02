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
                $this->call(LocationsDocumentsSeeder::class);
                $this->call(SiteConfigurationsSeeder::class);
                $this->call(DemoDBSeeder::class);
                $this->call(AdminActionsSeeder::class);
                DB::commit();
            });
        } catch (\Exception $error) {
            throw new \Exception($error);
        }
    }
}
