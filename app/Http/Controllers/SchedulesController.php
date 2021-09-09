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
    private $schedulesService;

    /**
     * SchedulesController constructor.
     * @param SchedulesService $schedulesService
     */
    public function __construct(SchedulesService $schedulesService)
    {
        $this->schedulesService = $schedulesService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getSchedules(Request $request, int $id = null)
    {
        return response($this->schedulesService->getSchedules($id), 200);
    }

    /**
     * @param ChangeOrCreateSchedule $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @param ChangeOrCreateSchedule $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
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

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteSchedules(Request $request, int $id)
    {
        return response($this->schedulesService->deleteSchedules($request->user(), $id), 200);
    }
}
