<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 16:11
 */

namespace App\Repositories;


use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class InformationCategoriesItemRepository
 * @package App\Repositories
 */
class ProductCategoriesRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return ProductCategory::class;
    }

    public function getByProductId($id): Collection
    {
        return $this->model->where('product_id', $id)->get();
    }
}
