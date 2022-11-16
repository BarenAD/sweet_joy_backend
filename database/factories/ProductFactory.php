<?php

namespace Database\Factories;

use App\Models\Product;

class ProductFactory extends CoreFactory
{
    protected $model = Product::class;

    protected array $availableUnit = [
        'Поштучно',
        'Фасовка 100гр',
        'Фасовка 500гр',
        'Фасовка 800гр',
        'Блок (12шт)',
        'На разновес',
        'Мешок 100кг',
    ];

    public function definition()
    {
        return $this->decorateTimestamp([
            'image' => 'demo/image_' . rand(1, 10) . '.jpg',
            'name' => $this->faker->text(50),
            'composition' => $this->faker->text(50),
            'manufacturer' => $this->faker->text(50),
            'description' => $this->faker->text(100),
            'product_unit' => $this->availableUnit[array_rand($this->availableUnit, 1)],
        ]);
    }
}
