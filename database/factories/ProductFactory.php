<?php

namespace Database\Factories;

use App\Models\Product;

class ProductFactory extends CoreFactory
{
    protected $model = Product::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'image' => "http://localhost/storage/favicon.ico",
            'name' => $this->faker->text(50),
            'composition' => $this->faker->text(50),
            'manufacturer' => $this->faker->text(50),
            'description' => $this->faker->text(100),
            'product_unit' => $this->faker->text(10),
        ]);
    }
}
