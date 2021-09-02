<?php


namespace App\Repositories;

use App\Models\Schedule;

/**
 * Class SchedulesRepository
 * @package App\Repositories
 */
class SchedulesRepository
{
    private $model;

    /**
     * SchedulesRepository constructor.
     * @param Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
    }

    /**
     * @param int|null $id
     * @return Schedule[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getSchedules(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
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
    public function create(
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
        return $this->model::create([
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
}
