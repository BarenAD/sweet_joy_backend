<?php


namespace App\Repositories;


use App\Models\ShopAssortment;

/**
 * Class ProductInformationRepository
 * @package App\Repositories
 */
class ShopAssortmentRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return ShopAssortment::class;
    }

    public function hasProductInShop(int $shopID, int $productID): bool
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->where('product_id', $productID)
            ->exists();
    }
}
