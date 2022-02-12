<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\PointOfSalePolicy;
use App\Repositories\PointOfSaleRepository;

/**
 * Class PointsOfSaleService
 * @package App\Http\Services
 */
class PointsOfSaleService
{
    private PointOfSaleRepository $pointOfSaleRepository;
    private PointOfSalePolicy $pointOfSalePolicy;

    public function __construct(
        PointOfSaleRepository $pointOfSaleRepository,
        PointOfSalePolicy $pointOfSalePolicy
    ) {
        $this->pointOfSaleRepository = $pointOfSaleRepository;
        $this->pointOfSalePolicy = $pointOfSalePolicy;
    }

    public function getPointsOfSale(int $id = null)
    {
        return $this->pointOfSaleRepository->getPointsOfSale($id);
    }

    public function createPointOfSale(
        User $user,
        int $id_schedule,
        string $address,
        string $phone,
        string $map_integration = null
    ) {
        if ($this->pointOfSalePolicy->canCreate($user)) {
            CacheService::cacheProductsInfo('delete', 'points_of_sale');
            return $this->pointOfSaleRepository->create($id_schedule, $address, $phone, $map_integration);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changePointOfSale(
        User $user,
        int $id,
        int $id_schedule,
        string $address,
        string $phone,
        string $map_integration = null
    ) {
        $pointOfSale = $this->pointOfSaleRepository->getPointsOfSale($id);
        if ($this->pointOfSalePolicy->canUpdate($user, $pointOfSale)) {
            $pointOfSale->fill([
                'id_s' => $id_schedule,
                'address' => $address,
                'phone' => $phone,
                'map_integration' => $map_integration,
            ])->save();
            CacheService::cacheProductsInfo('delete', 'points_of_sale');
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return $pointOfSale;
    }

    public function deletePointOfSale(User $user, int $id)
    {
        $pointOfSale = $this->pointOfSaleRepository->getPointsOfSale($id);
        if ($this->pointOfSalePolicy->canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'points_of_sale');
            return $pointOfSale->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
