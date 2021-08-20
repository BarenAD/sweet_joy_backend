<?php

namespace App\Http\Requests;

use App\Http\Services\RequestMessageGenerator;
use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreateSchedule extends FormRequest
{
    private $arrayRules;
    private $requestMessageGenerator;

    /**
     * ChangeOrCreateAdminInfo constructor.
     * @param RequestMessageGenerator $messageGen
     */
    public function __construct(RequestMessageGenerator $messageGen)
    {
        parent::__construct();
        $this->arrayRules = [
            'id' => 'numeric',
            'name' => 'required|string|max:255',
            'monday' => 'required|string|max:255',
            'tuesday' => 'required|string|max:255',
            'wednesday' => 'required|string|max:255',
            'thursday' => 'required|string|max:255',
            'friday' => 'required|string|max:255',
            'saturday' => 'required|string|max:255',
            'sunday' => 'required|string|max:255',
            'holiday' => 'required|string|max:255',
            'particular' => 'required|string|max:255',
        ];
        $this->requestMessageGenerator = $messageGen;
    }

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
        return $this->arrayRules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return $this->requestMessageGenerator->generatedMessages($this->arrayRules);
    }
}
