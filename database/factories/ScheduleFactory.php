<?php

namespace Database\Factories;

use App\Models\Schedule;

class ScheduleFactory extends CoreFactory
{
    protected $model = Schedule::class;

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'monday' => $this->faker->text(100),
            'tuesday' => $this->faker->text(100),
            'wednesday' => $this->faker->text(100),
            'thursday' => $this->faker->text(100),
            'friday' => $this->faker->text(100),
            'saturday' => $this->faker->text(100),
            'sunday' => $this->faker->text(100),
            'holiday' => $this->faker->text(100),
            'particular' => $this->faker->text(100),
        ]);
    }
}
