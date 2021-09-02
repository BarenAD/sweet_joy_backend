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
    private $schedulesRepository;

    /**
     * SchedulesService constructor.
     * @param SchedulesRepository $schedulesRepository
     */
    public function __construct(SchedulesRepository $schedulesRepository)
    {
        $this->schedulesRepository = $schedulesRepository;
    }

    /**
     * @param int|null $id
     * @return SchedulesRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSchedules(int $id = null)
    {
        return $this->schedulesRepository->getSchedules($id);
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $monday
     * @param string $tuesday
     * @param string $wednesday
     * @param string $thursday
     * @param string $friday
     * @param string $saturday
     * @param string $sunday
     * @param string $holiday
     * @param string $particular
     * @return mixed
     */
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
    )
    {
        if (SchedulesPolicy::canCreate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @param string $name
     * @param string $monday
     * @param string $tuesday
     * @param string $wednesday
     * @param string $thursday
     * @param string $friday
     * @param string $saturday
     * @param string $sunday
     * @param string $holiday
     * @param string $particular
     * @return SchedulesRepository[]|\Illuminate\Database\Eloquent\Collection
     */
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
    )
    {
        if (SchedulesPolicy::canUpdate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteSchedules(User $user, int $id)
    {
        if (SchedulesPolicy::canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'schedules');
            return $this->schedulesRepository->getSchedules($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
