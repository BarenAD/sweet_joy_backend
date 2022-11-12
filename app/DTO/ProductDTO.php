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
    public array $categories;

    public static function formRequest(FormRequest $request): self
    {
        return new self([
            'image' => $request->file('image', null),
            'name' => $request->get('name'),
            'composition' => $request->get('composition'),
            'manufacturer' => $request->get('manufacturer'),
            'description' => $request->get('description'),
            'product_unit' => $request->get('product_unit'),
            'categories' => $request->get('categories'),
        ]);
    }

    public static function make(array $params): self
    {
        return new self([
            'image' =>  $params['image'] ?? null,
            'name' =>  $params['name'] ?? null,
            'composition' =>  $params['composition'] ?? null,
            'manufacturer' =>  $params['manufacturer'] ?? null,
            'description' =>  $params['description'] ?? null,
            'product_unit' =>  $params['product_unit'] ?? null,
            'categories' =>  $params['categories'] ?? null,
        ]);
    }
}
