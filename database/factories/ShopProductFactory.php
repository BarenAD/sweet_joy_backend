<?php

namespace Database\Factories;

use App\Models\ShopProduct;

class ShopProductFactory extends CoreFactory
{
    protected $model = ShopProduct::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'price' => rand(0, 100000),
            'count' => rand(0, 100000),
        ]);
    }
}
