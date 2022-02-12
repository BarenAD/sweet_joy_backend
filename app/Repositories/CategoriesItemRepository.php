<?php


namespace App\Repositories;


use App\Models\CategoryItem;

/**
 * Class CategoriesItemRepository
 * @package App\Repositories
 */
class CategoriesItemRepository
{
    private CategoryItem $model;

    public function __construct(CategoryItem $categoryItem)
    {
        $this->model = $categoryItem;
    }

    public function getCategoriesItem(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function create(string $name)
    {
        return $this->model::create([
            'name' => $name
        ]);
    }
}
