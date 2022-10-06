<?php

use App\Models\DocumentLocation;
use Illuminate\Database\Seeder;

class DocumentLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DocumentLocation::insert([
            [
                'id' => 1,
                'name' => 'документ в верхнем баре на главной',
                'identify' => 'main_top_bar_document',
            ],
        ]);
    }
}
