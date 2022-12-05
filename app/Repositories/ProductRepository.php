<?php


namespace App\Repositories;



use App\Models\Product;

/**
 * Class ItemsRepository
 * @package App\Repositories
 */
class ProductRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Product::class;
    }
}
