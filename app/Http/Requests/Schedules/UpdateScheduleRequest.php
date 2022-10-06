<?php

namespace App\Http\Requests\Schedules;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
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
