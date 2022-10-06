<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedules\DestroyScheduleRequest;
use App\Http\Requests\Schedules\IndexScheduleRequest;
use App\Http\Requests\Schedules\StoreScheduleRequest;
use App\Http\Requests\Schedules\UpdateScheduleRequest;
use App\Repositories\ScheduleRepository;

/**
 * Class SchedulesController
 * @package App\Http\Controllers
 */
class ScheduleController extends Controller
{
    private ScheduleRepository $scheduleRepository;

    public function __construct(ScheduleRepository $scheduleRepository)
    {
        $this->scheduleRepository = $scheduleRepository;
    }

    public function index(IndexScheduleRequest $request)
    {
        return response($this->scheduleRepository->getAll(), 200);
    }

    public function show(IndexScheduleRequest $request, int $id)
    {
        return response($this->scheduleRepository->find($id), 200);
    }

    public function store(StoreScheduleRequest $request)
    {
        return response($this->scheduleRepository->store($request->validated()), 200);
    }

    public function update(UpdateScheduleRequest $request, int $id)
    {
        return response($this->scheduleRepository->update($id, $request->validated()), 200);
    }

    public function destroy(DestroyScheduleRequest $request, int $id)
    {
        return response($this->scheduleRepository->destroy($id), 200);
    }
}
