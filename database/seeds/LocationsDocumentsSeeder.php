<?php

use App\Models\LocationsDocuments;
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
                'id' => 1,
                'name' => 'документ в верхнем баре на главной',
                'identify' => 'main_top_bar_document',
            ],
        ]);
    }
}
