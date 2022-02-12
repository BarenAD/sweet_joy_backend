<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:40
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\SchedulesPolicy;
use App\Repositories\SchedulesRepository;

/**
 * Class SchedulesService
 * @package App\Http\Services
 */
class SchedulesService
{
    private SchedulesRepository $schedulesRepository;
    private SchedulesPolicy $schedulesPolicy;

    public function __construct(
        SchedulesRepository $schedulesRepository,
        SchedulesPolicy $schedulesPolicy
    ) {
        $this->schedulesRepository = $schedulesRepository;
        $this->schedulesPolicy = $schedulesPolicy;
    }

    public function getSchedules(int $id = null)
    {
        return $this->schedulesRepository->getSchedules($id);
    }

    public function createSchedule(
        User $user,
        string $name,
        string $monday,
        string $tuesday,
        string $wednesday,
        string $thursday,
        string $friday,
        string $saturday,
        string $sunday,
        string $holiday = null,
        string $particular = null
    ){
        if ($this->schedulesPolicy->canCreate($user)) {
            CacheService::cacheProductsInfo('delete', 'schedules');
            return $this->schedulesRepository->create(
                 $name,
                 $monday,
                 $tuesday,
                 $wednesday,
                 $thursday,
                 $friday,
                 $saturday,
                 $sunday,
                 $holiday,
                 $particular
            );
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeSchedule(
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
        string $holiday = null,
        string $particular = null
    ){
        if ($this->schedulesPolicy->canUpdate($user)) {
            $schedule = $this->schedulesRepository->getSchedules($id);
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
            CacheService::cacheProductsInfo('delete', 'schedules');
            return $schedule;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function deleteSchedules(User $user, int $id)
    {
        if ($this->schedulesPolicy->canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'schedules');
            return $this->schedulesRepository->getSchedules($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
