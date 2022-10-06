<?php


namespace App\Repositories;


use App\Models\Shop;

/**
 * Class ShopRepository
 * @package App\Repositories
 */
class ShopRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Shop::class;
    }
}
