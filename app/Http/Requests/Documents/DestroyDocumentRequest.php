<?php

namespace App\Http\Requests\Documents;

use App\Policies\DocumentPolicy;
use Illuminate\Foundation\Http\FormRequest;

class DestroyDocumentRequest extends FormRequest
{
    public function authorize(DocumentPolicy $documentPolicy)
    {
        return $documentPolicy->canDestroy();
    }
    
    public function rules()
    {
        return [];
    }
}
