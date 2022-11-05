<?php


namespace App\Repositories;


use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

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

    public function getAllWithSchedules(bool $withSchedules = true): Collection
    {
        return $this->model->withSchedules($withSchedules)->get();
    }
}
