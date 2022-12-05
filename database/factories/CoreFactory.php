<?php


namespace Database\Factories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

abstract class CoreFactory extends Factory
{
    protected function decorateTimestamp(array $params = []): array
    {
        return array_merge(
            $params,
            $this->generateTimestamp(),
        );
    }

    protected function generateTimestamp(string $format = 'Y-m-d h:i:s'): array
    {
        return [
            'created_at' => $this->generateRandomDate($format),
            'updated_at' => $this->generateRandomDate($format),
        ];
    }

    protected function generateRandomDate(string $format = 'Y-m-d h:i:s'): string
    {
        return Carbon::create(random_int(2017, (int) now()->year))
            ->endOfYear()
            ->subDays(random_int(1, 365))
            ->format($format);
    }
}
