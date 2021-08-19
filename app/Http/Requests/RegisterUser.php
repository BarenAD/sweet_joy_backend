<?php

namespace App\Http\Requests;

use App\Http\services\RequestMessageGenerator;
use Illuminate\Foundation\Http\FormRequest;

class RegisterUser extends FormRequest
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
            'fio' => 'required|string|max:255',
            'login' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'phone' => 'required|regex:/^[7]\d{10}$/',
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
