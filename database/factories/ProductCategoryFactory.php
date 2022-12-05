<?php

namespace Database\Factories;

use App\Models\ProductCategory;

class ProductCategoryFactory extends CoreFactory
{
    protected $model = ProductCategory::class;

    public function definition()
    {
        return $this->decorateTimestamp();
    }
}
