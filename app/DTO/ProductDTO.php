<?php

namespace App\DTO;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class ProductDTO extends DataTransferObject
{
    public ?UploadedFile $image;
    public string $name;
    public string $composition;
    public string $manufacturer;
    public string $description;
    public string $product_unit;
    public array $product_categories;

    public static function formRequest(FormRequest $request): self
    {
        return new self([
            'image' => $request->file('image', null),
            'name' => $request->get('name'),
            'composition' => $request->get('composition'),
            'manufacturer' => $request->get('manufacturer'),
            'description' => $request->get('description'),
            'product_unit' => $request->get('product_unit'),
            'product_categories' => $request->get('product_categories'),
        ]);
    }
}
