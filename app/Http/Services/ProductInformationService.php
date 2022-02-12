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
    private ProductInformationRepository $productInformationRepository;
    private ProductInformationPolicy $productInformationPolicy;

    public function __construct(
        ProductInformationRepository $productInformationRepository,
        ProductInformationPolicy $productInformationPolicy
    ) {
        $this->productInformationRepository = $productInformationRepository;
        $this->productInformationPolicy = $productInformationPolicy;
    }

    public function getProductsInfo(int $id = null)
    {
        return $this->productInformationRepository->getProductsInformation($id);
    }

    public function createProductInfo(
        User $user,
        int $id_item,
        int $id_point_of_Sale,
        int $price = null,
        int $count = null
    ) {
        if ($this->productInformationPolicy->canCreate($user, $id_point_of_Sale)) {
            CacheService::cacheProductsInfo('delete', 'products');
            return $this->productInformationRepository->create($id_item, $id_point_of_Sale, $price, $count);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeProductInfo(
        User $user,
        int $id,
        int $id_i,
        int $id_pos,
        int $price = null,
        int $count = null
    ) {
        $productInformation = $this->productInformationRepository->getProductsInformation($id);
        if ($this->productInformationPolicy->canUpdateDelete($user, $productInformation)) {
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

    public function deleteProductInfo(User $user, int $id)
    {
        $productInformation = $this->productInformationRepository->getProductsInformation($id);
        if ($this->productInformationPolicy->canUpdateDelete($user, $productInformation)) {
            CacheService::cacheProductsInfo('delete', 'products');
            return $productInformation->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
