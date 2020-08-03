<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateSchedule;
use App\Repositories\SchedulesRepository;
use Illuminate\Http\Request;

class PointsOfSaleSchedulesController extends Controller
{
    private $schedulesRepository;

    public function __construct(SchedulesRepository $schedulesRepository)
    {
        $this->schedulesRepository = $schedulesRepository;
    }

    public function getSchedules(Request $request) {
        return response($this->schedulesRepository->getSchedules($request->get('id')), 200);
    }

    public function createSchedule(ChangeOrCreateSchedule $request) {
        return response(
            $this->schedulesRepository->createSchedule(
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

    public function changeSchedule(ChangeOrCreateSchedule $request) {
        return response(
            $this->schedulesRepository->changeSchedule(
                (int) $request->get('id'),
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

    public function deleteSchedules(Request $request) {
        return response($this->schedulesRepository->deleteSchedules($request->get('id')), 200);
    }
}
