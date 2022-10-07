<?php

namespace App\Policies;

class SchedulePolicy extends CorePolicy
{
    protected function setUpParams(): void
    {
        $this->baseRule = 'schedules';
    }
}
