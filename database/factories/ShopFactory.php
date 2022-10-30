<?php

namespace Database\Factories;

use App\Models\Category;

class ShopFactory extends CoreFactory
{
    protected $model = Category::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'address' => $this->faker->address(),
            'phone' => $this->faker->regexify('/^[7]\d{10}$/'),
            'map_integration' => $this->faker->text(100),
        ]);
    }
}
