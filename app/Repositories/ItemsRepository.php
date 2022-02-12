<?php


namespace App\Repositories;



use App\Models\Item;

/**
 * Class ItemsRepository
 * @package App\Repositories
 */
class ItemsRepository
{
    private Item $model;

    public function __construct(Item $item)
    {
        $this->model = $item;
    }

    public function getItems(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function create(
        string $picture,
        string $miniature_picture,
        string $name,
        string $composition = null,
        string $manufacturer = null,
        string $description = null,
        string $product_unit = null
    ){
        return $this->model::create([
            'picture' => $picture,
            'miniature_picture' => $miniature_picture,
            'name' => $name,
            'composition' => $composition,
            'manufacturer' => $manufacturer,
            'description' => $description,
            'product_unit' => $product_unit
        ]);
    }
}
