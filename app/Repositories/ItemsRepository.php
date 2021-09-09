<?php


namespace App\Repositories;



use App\Models\Item;

/**
 * Class ItemsRepository
 * @package App\Repositories
 */
class ItemsRepository
{
    private $model;

    /**
     * ItemsRepository constructor.
     * @param Item $item
     */
    public function __construct(Item $item)
    {
        $this->model = $item;
    }

    /**
     * @param int|null $id
     * @return Item[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getItems(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param string $picture
     * @param string $miniature_picture
     * @param string $name
     * @param string $composition
     * @param string $manufacturer
     * @param string $description
     * @param string $product_unit
     * @return mixed
     */
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
