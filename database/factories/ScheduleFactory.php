<?php

namespace Database\Factories;

use App\Models\Schedule;

class ScheduleFactory extends CoreFactory
{
    protected $model = Schedule::class;

    protected array $availableDayValue = [
        'С 9:00 до 18:00. Обед с 13:00 до 14:00.',
        'С 11:00 до 15:00. Без обеда.',
        'С 9:00 до 22:00. Обед с 15:00 до 16:00.',
        'Выходной.',
        'С 6:00 до 12:00. Перерыв с 12:00 до 16:00. С 16:00 до 22:00.',
    ];

    public function definition()
    {
        return $this->decorateTimestamp([
            'name' => $this->faker->text(100),
            'monday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'tuesday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'wednesday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'thursday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'friday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'saturday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'sunday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'holiday' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
            'particular' => $this->availableDayValue[array_rand($this->availableDayValue, 1)],
        ]);
    }
}
