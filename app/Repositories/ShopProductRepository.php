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

    public function getShopProducts(int $shopId): Collection
    {
        return $this->model
            ->where('shop_id', $shopId)
            ->get();
    }

    public function findByShop(int $shopId, int $id): Model
    {
        return $this->model
            ->where('shop_id', $shopId)
            ->findOrFail($id);
    }

    public function updateByShop(int $shopId, int $id, array $params = []): Model
    {
        $modelItem = $this->model
            ->where('shop_id', $shopId)
            ->where('id', $id)
            ->firstOrFail();

        return tap($modelItem)->update($params);
    }

    public function destroyByShop(int $shopId, int $id): int
    {
        return $this->model
            ->where('shop_id', $shopId)
            ->where('id', $id)
            ->delete();
    }
}
