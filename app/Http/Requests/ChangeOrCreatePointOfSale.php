<?php

namespace App\Http\Requests;

use App\Http\Services\RequestMessageGenerator;
use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreatePointOfSale extends FormRequest
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
            'id_s' => 'required|numeric',
            'address' => 'required|string|max:255',
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
