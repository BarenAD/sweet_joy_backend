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
                'name' => 'Футер первый блок слева',
                'identify' => 'footer_first',
            ],
            [
                'name' => 'Футер второй блок слева',
                'identify' => 'footer_second',
            ],
            [
                'name' => 'Футер третий блок слева',
                'identify' => 'footer_third',
            ],
            [
                'name' => 'Футер четвёртый блок слева',
                'identify' => 'footer_fourth',
            ],
            [
                'name' => 'Шапка первый блок справа (преимущества)',
                'identify' => 'header_last',
            ],
        ]);
    }
}
