<?php

namespace Database\Factories;

use App\Models\SiteConfiguration;

class SiteConfigurationFactory extends CoreFactory
{
    protected $model = SiteConfiguration::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'identify' => uniqid('identify_'),
            'value' => $this->faker->text(100),
        ]);
    }
}
