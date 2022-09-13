<?php

namespace Database\Factories;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $date = Carbon::create(random_int(2017, 2020))->endOfYear()->subDays(random_int(1, 365));
        return [
            "name" => $this->faker->text(100),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
