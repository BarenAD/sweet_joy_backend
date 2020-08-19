<?php


namespace App\Repositories;

use App\Http\services\GeneratedAborting;
use App\Models\Schedule;
use App\Models\User;
use App\Policies\SchedulesPolicy;

class SchedulesRepository
{
    public static function getSchedules(int $id = null)
    {
        if (isset($id)) {
            return Schedule::findOrFail($id);
        }
        return Schedule::all();
    }

    public static function createSchedule(
        User $user,
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
        if (SchedulesPolicy::canCreate($user)) {
            CacheRepository::cacheProductsInfo('delete', 'schedules');
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
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public static function changeSchedule(
        User $user,
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
        if (SchedulesPolicy::canUpdate($user)) {
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
            CacheRepository::cacheProductsInfo('delete', 'schedules');
            return $schedule;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public static function deleteSchedules(User $user, int $id)
    {
        if (SchedulesPolicy::canDelete($user)) {
            CacheRepository::cacheProductsInfo('delete', 'schedules');
            return Schedule::findOrFail($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
