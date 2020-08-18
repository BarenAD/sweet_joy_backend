<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateSchedule;
use App\Repositories\SchedulesRepository;
use Illuminate\Http\Request;

class PointsOfSaleSchedulesController extends Controller
{
    public function getSchedules(Request $request) {
        return response(SchedulesRepository::getSchedules($request->get('id')), 200);
    }

    public function createSchedule(ChangeOrCreateSchedule $request) {
        return response(
            SchedulesRepository::createSchedule(
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

    public function changeSchedule(ChangeOrCreateSchedule $request) {
        return response(
            SchedulesRepository::changeSchedule(
                $request->user(),
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
        return response(SchedulesRepository::deleteSchedules($request->user(), $request->get('id')), 200);
    }
}
