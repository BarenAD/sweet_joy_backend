<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateSchedule;
use App\Http\Services\SchedulesService;
use Illuminate\Http\Request;

/**
 * Class SchedulesController
 * @package App\Http\Controllers
 */
class SchedulesController extends Controller
{
    private SchedulesService $schedulesService;

    public function __construct(SchedulesService $schedulesService)
    {
        $this->schedulesService = $schedulesService;
    }

    public function getSchedules(Request $request, int $id = null)
    {
        return response($this->schedulesService->getSchedules($id), 200);
    }

    public function createSchedule(ChangeOrCreateSchedule $request)
    {
        return response(
            $this->schedulesService->createSchedule(
                $request->user(),
                $request->get('name'),
                $request->get('monday'),
                $request->get('tuesday'),
                $request->get('wednesday'),
                $request->get('thursday'),
                $request->get('friday'),
                $request->get('saturday'),
                $request->get('sunday'),
                $request->get('holiday'),
                $request->get('particular')
            ),
            200
        );
    }

    public function changeSchedule(ChangeOrCreateSchedule $request, int $id)
    {
        return response(
            $this->schedulesService->changeSchedule(
                $request->user(),
                $id,
                $request->get('name'),
                $request->get('monday'),
                $request->get('tuesday'),
                $request->get('wednesday'),
                $request->get('thursday'),
                $request->get('friday'),
                $request->get('saturday'),
                $request->get('sunday'),
                $request->get('holiday'),
                $request->get('particular')
            ),
            200
        );
    }

    public function deleteSchedules(Request $request, int $id)
    {
        return response($this->schedulesService->deleteSchedules($request->user(), $id), 200);
    }
}
