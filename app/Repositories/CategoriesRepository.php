<?php


namespace App\Repositories;


use App\Models\Category;

/**
 * Class CategoriesItemRepository
 * @package App\Repositories
 */
class CategoriesRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Category::class;
    }
}
