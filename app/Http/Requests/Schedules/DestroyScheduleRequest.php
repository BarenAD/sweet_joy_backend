<?php

namespace App\Http\Requests\Schedules;

use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyScheduleRequest extends FormRequest
{
    public function authorize(SchedulePolicy $schedulePolicy)
    {
        return $schedulePolicy->canDestroy();
    }

    public function rules()
    {
        return [];
    }
}
