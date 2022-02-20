<?php


namespace App\Repositories;



use App\Models\Product;

/**
 * Class ItemsRepository
 * @package App\Repositories
 */
class ProductsRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Product::class;
    }
}
