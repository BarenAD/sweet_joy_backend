<?php


namespace App\Repositories;


use App\Models\ShopProduct;

/**
 * Class ProductInformationRepository
 * @package App\Repositories
 */
class ShopProductRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return ShopProduct::class;
    }

    public function hasProductInShop(int $shopID, int $productID): bool
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->where('product_id', $productID)
            ->exists();
    }
}
