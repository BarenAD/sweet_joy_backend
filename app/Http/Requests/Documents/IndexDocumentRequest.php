<?php

namespace App\Http\Requests\Documents;

use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Http\FormRequest;

class IndexDocumentRequest extends FormRequest
{
    public function authorize(DocumentPolicy $documentPolicy)
    {
        return $documentPolicy->canIndex();
    }

    public function rules()
    {
        return [];
    }
}
