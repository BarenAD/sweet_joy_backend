<?php

use App\LocationsDocuments;
use Illuminate\Database\Seeder;

class LocationsDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LocationsDocuments::insert([
            [
                'name' => 'документ в верхнем баре на главной',
                'identify' => 'main_top_bar_document',
            ],
        ]);
    }
}
