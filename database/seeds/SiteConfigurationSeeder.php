<?php

use App\Models\SiteConfiguration;
use Illuminate\Database\Seeder;

class SiteConfigurationSeeder extends Seeder
{
    private array $inserting = [
        [
            'id' => 1,
            'name' => 'Футер первый блок слева',
            'identify' => SiteConfiguration::FOOTER_FIRST,
        ],
        [
            'id' => 2,
            'name' => 'Футер второй блок слева',
            'identify' => SiteConfiguration::FOOTER_SECOND,
        ],
        [
            'id' => 3,
            'name' => 'Футер третий блок слева',
            'identify' => SiteConfiguration::FOOTER_THIRD,
        ],
        [
            'id' => 4,
            'name' => 'Шапка первый блок справа (преимущества)',
            'identify' => SiteConfiguration::HEADER_LAST,
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
