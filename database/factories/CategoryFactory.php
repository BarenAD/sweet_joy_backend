<?php

namespace Database\Factories;

use App\Models\Category;

class CategoryFactory extends CoreFactory
{
    protected $model = Category::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => uniqid(),
        ]);
    }
}
