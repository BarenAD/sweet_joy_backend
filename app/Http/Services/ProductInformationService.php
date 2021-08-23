<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 15:41
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\ProductInformationPolicy;
use App\Repositories\ProductInformationRepository;

/**
 * Class ProductInformationService
 * @package App\Http\Services
 */
class ProductInformationService
{
    private $productInformationRepository;

    /**
     * ProductInformationService constructor.
     * @param ProductInformationRepository $productInformationRepository
     */
    public function __construct(ProductInformationRepository $productInformationRepository)
    {
        $this->productInformationRepository = $productInformationRepository;
    }

    /**
     * @param int|null $id
     * @return ProductInformationRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getProductsInfo(int $id = null) {
        return $this->productInformationRepository->getProductsInformation($id);
    }

    /**
     * @param User $user
     * @param int $id_item
     * @param int $id_point_of_Sale
     * @param int $price
     * @param int $count
     * @return mixed
     */
    public function createProductInfo(
        User $user,
        int $id_item,
        int $id_point_of_Sale,
        int $price,
        int $count
    ) {
        if (ProductInformationPolicy::canCreate($user, $id_point_of_Sale)) {
            CacheService::cacheProductsInfo('delete', 'products');
            return $this->productInformationRepository->create($price, $count, $id_item, $id_point_of_Sale);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @param int $id_i
     * @param int $id_pos
     * @param int $price
     * @param int $count
     * @return ProductInformationRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeProductInfo(
        User $user,
        int $id,
        int $id_i,
        int $id_pos,
        int $price,
        int $count
    ) {
        $productInformation = $this->productInformationRepository->getProductsInformation($id);
        if (ProductInformationPolicy::canUpdateDelete($user, $productInformation)) {
            $productInformation->fill([
                'price' => $price,
                'count' => $count,
                'id_i' => $id_i,
                'id_pos' => $id_pos,
            ])->save();
            CacheService::cacheProductsInfo('delete', 'products');
            return $productInformation;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteProductInfo(User $user, int $id) {
        $productInformation = $this->productInformationRepository->getProductsInformation($id);
        if (ProductInformationPolicy::canUpdateDelete($user, $productInformation)) {
            CacheService::cacheProductsInfo('delete', 'products');
            return $productInformation->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
