<?php

namespace App\Http\Requests\Documents\Locations;

use App\Policies\DocumentLocationPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexDocumentLocationRequest extends FormRequest
{
    public function authorize(DocumentLocationPolicy $documentLocationPolicy)
    {
        return $documentLocationPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
