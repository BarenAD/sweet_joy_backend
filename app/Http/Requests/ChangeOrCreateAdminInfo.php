<?php

namespace App\Http\Requests;

use App\Http\services\RequestMessageGenerator;
use App\Rules\NumericKeysArray;
use Illuminate\Foundation\Http\FormRequest;

class ChangeOrCreateAdminInfo extends FormRequest
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
            'id_u' => 'required|numeric',
            'ids_pos' => new NumericKeysArray('ids_pos'),
            'ids_pos.*.*' => 'numeric',
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
