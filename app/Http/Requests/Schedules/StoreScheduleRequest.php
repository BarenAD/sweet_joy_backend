<?php

namespace App\Http\Requests\Schedules;

use App\Policies\SchedulePolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(SchedulePolicy $schedulePolicy)
    {
        return $schedulePolicy->canStore();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'monday' => 'required|string|max:255',
            'tuesday' => 'required|string|max:255',
            'wednesday' => 'required|string|max:255',
            'thursday' => 'required|string|max:255',
            'friday' => 'required|string|max:255',
            'saturday' => 'required|string|max:255',
            'sunday' => 'required|string|max:255',
            'holiday' => 'string|max:255',
            'particular' => 'string|max:255',
        ];
    }
}
