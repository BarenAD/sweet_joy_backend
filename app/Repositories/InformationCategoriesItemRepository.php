<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 16:11
 */

namespace App\Repositories;


use App\Models\InformationCategorieItem;

/**
 * Class InformationCategoriesItemRepository
 * @package App\Repositories
 */
class InformationCategoriesItemRepository
{
    private InformationCategorieItem $model;

    public function __construct(InformationCategorieItem $informationCategorieItem)
    {
        $this->model = $informationCategorieItem;
    }

    public function getInformationCategories(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function getInfoCategoriesGroupByItem()
    {
        return $this->model::all()->groupBy('id_i');
    }

    public function getInfoCategoriesOnIDItem(int $id_item)
    {
        return $this->model::where('id_i', $id_item)->get();
    }

    public function create(int $id_item, int $id_category_item)
    {
        return $this->model::create([
            'id_i' => $id_item,
            'id_ci' => $id_category_item
        ]);
    }
}
