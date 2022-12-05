<?php

namespace App\Http\Requests\Documents;

use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(DocumentPolicy $documentPolicy)
    {
        return $documentPolicy->canStore();
    }

    public function rules()
    {
        return [
            'document' => 'required|mimes:pdf|max:20000',
            'name' => 'required|string|max:255',
        ];
    }
}
