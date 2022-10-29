<?php


namespace App\Repositories;


use App\Models\ShopProduct;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function getShopProducts(int $shopID): Collection
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->get();
    }

    public function findByShop(int $shopID, int $id): Model
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->findOrFail($id);
    }

    public function updateByShop(int $shopID, int $id, array $params = []): Model
    {
        $modelItem = $this->model
            ->where('shop_id', $shopID)
            ->where('id', $id)
            ->firstOrFail();

        return tap($modelItem)->update($params);
    }

    public function destroyByShop(int $shopID, int $id): int
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->where('id', $id)
            ->delete();
    }

    public function hasProductInShop(int $shopID, int $productID): bool
    {
        return $this->model
            ->where('shop_id', $shopID)
            ->where('product_id', $productID)
            ->exists();
    }

    public function getAllGroupShop()
    {
        return $this->model->get()->groupBy('shop_id');
    }

    public function getAllGroupProduct()
    {
        return $this->model->get()->groupBy('product_id');
    }
}
