<?php

use App\Models\SiteConfigurations;
use Illuminate\Database\Seeder;

class SiteConfigurationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteConfigurations::insert([
            [
                'id' => 1,
                'name' => 'Футер первый блок слева',
                'identify' => 'footer_first',
            ],
            [
                'id' => 2,
                'name' => 'Футер второй блок слева',
                'identify' => 'footer_second',
            ],
            [
                'id' => 3,
                'name' => 'Футер третий блок слева',
                'identify' => 'footer_third',
            ],
            [
                'id' => 4,
                'name' => 'Шапка первый блок справа (преимущества)',
                'identify' => 'header_last',
            ],
        ]);
    }
}
