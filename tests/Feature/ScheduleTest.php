<?php


namespace Tests\Feature;


use App\Models\Schedule;
use Tests\TestApiResource;

class ScheduleTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.schedules';
        $this->model = new Schedule();
    }
}
