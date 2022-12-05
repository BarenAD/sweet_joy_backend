<?php

namespace App\Http\Requests\Documents\Locations;

use App\Policies\DocumentLocationPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentLocationRequest extends FormRequest
{
    public function authorize(DocumentLocationPolicy $documentLocationPolicy)
    {
        return $documentLocationPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'document_id' => 'required|numeric',
        ];
    }
}
