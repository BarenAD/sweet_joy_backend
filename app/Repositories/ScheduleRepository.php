<?php


namespace App\Repositories;

use App\Models\Schedule;


class ScheduleRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Schedule::class;
    }
}
