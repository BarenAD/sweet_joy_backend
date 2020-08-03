<?php


namespace App\Repositories;

use App\Schedule;

class SchedulesRepository
{
    public function getSchedules(int $id = null)
    {
        if (isset($id)) {
            return Schedule::find($id);
        }
        return Schedule::all();
    }

    public function createSchedule(
        string $name,
        string $monday,
        string $tuesday,
        string $wednesday,
        string $thursday,
        string $friday,
        string $saturday,
        string $sunday,
        string $holiday,
        string $particular
    )
    {
        return Schedule::create([
            'name' => $name,
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday,
            'holiday' => $holiday,
            'particular' => $particular
        ]);
    }

    public function changeSchedule(
        int $id,
        string $name,
        string $monday,
        string $tuesday,
        string $wednesday,
        string $thursday,
        string $friday,
        string $saturday,
        string $sunday,
        string $holiday,
        string $particular
    )
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->fill([
            'name' => $name,
            'monday' => $monday,
            'tuesday' => $tuesday,
            'wednesday' => $wednesday,
            'thursday' => $thursday,
            'friday' => $friday,
            'saturday' => $saturday,
            'sunday' => $sunday,
            'holiday' => $holiday,
            'particular' => $particular
        ])->save();
        return $schedule;
    }

    public function deleteSchedules(int $id)
    {
        return Schedule::findOrFail($id)->delete();
    }
}
