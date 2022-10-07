<?php

namespace App\Http\Requests\Schedules;

use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexScheduleRequest extends FormRequest
{
    public function authorize(SchedulePolicy $schedulePolicy)
    {
        return $schedulePolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
