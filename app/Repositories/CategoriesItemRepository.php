<?php


namespace App\Repositories;


use App\Models\CategoryItem;

/**
 * Class CategoriesItemRepository
 * @package App\Repositories
 */
class CategoriesItemRepository
{
    private $model;

    /**
     * CategoryItemRepository constructor.
     * @param CategoryItem $categoryItem
     */
    public function __construct(CategoryItem $categoryItem)
    {
        $this->model = $categoryItem;
    }

    /**
     * @param int|null $id
     * @return CategoryItem[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCategoriesItem(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function create(string $name)
    {
        return $this->model::create([
            'name' => $name
        ]);
    }
}
