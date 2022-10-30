<?php

namespace Database\Factories;

use App\Models\Shop;

class ShopFactory extends CoreFactory
{
    protected $model = Shop::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'address' => $this->faker->address(),
            'phone' => $this->faker->regexify('/^[7]\d{10}$/'),
            'map_integration' => $this->faker->text(100),
            'schedule_id' => null,
        ]);
    }
}
