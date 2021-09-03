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
    private $pointOfSaleRepository;

    /**
     * PointsOfSaleService constructor.
     * @param PointOfSaleRepository $pointOfSaleRepository
     */
    public function __construct(PointOfSaleRepository $pointOfSaleRepository)
    {
        $this->pointOfSaleRepository = $pointOfSaleRepository;
    }

    /**
     * @param int|null $id
     * @return PointOfSaleRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPointsOfSale(int $id = null)
    {
        return $this->pointOfSaleRepository->getPointsOfSale($id);
    }

    /**
     * @param User $user
     * @param int $id_schedule
     * @param string $address
     * @param string $phone
     * @param string|null $map_integration
     * @return mixed
     */
    public function createPointOfSale(
        User $user,
        int $id_schedule,
        string $address,
        string $phone,
        string $map_integration = null
    ) {
        if (PointOfSalePolicy::canCreate($user)) {
            CacheService::cacheProductsInfo('delete', 'points_of_sale');
            return $this->pointOfSaleRepository->create($id_schedule, $address, $phone, $map_integration);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @param int $id_schedule
     * @param string $address
     * @param string $phone
     * @param string|null $map_integration
     * @return PointOfSaleRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changePointOfSale(
        User $user,
        int $id,
        int $id_schedule,
        string $address,
        string $phone,
        string $map_integration = null
    ) {
        $pointOfSale = $this->pointOfSaleRepository->getPointsOfSale($id);
        if (PointOfSalePolicy::canUpdate($user, $pointOfSale)) {
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

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deletePointOfSale(User $user, int $id)
    {
        $pointOfSale = $this->pointOfSaleRepository->getPointsOfSale($id);
        if (PointOfSalePolicy::canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'points_of_sale');
            return $pointOfSale->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
