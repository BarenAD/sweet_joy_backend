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
                'name' => 'каталог продуктов на главной',
                'identify' => 'main_product_catalog',
            ],
        ]);
    }
}
