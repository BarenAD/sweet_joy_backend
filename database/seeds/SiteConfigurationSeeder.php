<?php

use App\Models\SiteConfiguration;
use Illuminate\Database\Seeder;

class SiteConfigurationSeeder extends Seeder
{
    private array $inserting = [
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
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->inserting as $params) {
            $newModel = new SiteConfiguration($params);
            if ($newModel->exists()) {
                $newModel->update();
            } else {
                $newModel->save();
            }
        }
    }
}
