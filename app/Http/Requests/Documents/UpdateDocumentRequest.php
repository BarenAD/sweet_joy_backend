<?php

namespace App\Http\Requests\Documents;

use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(DocumentPolicy $documentPolicy)
    {
        return $documentPolicy->canUpdate();
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
}
