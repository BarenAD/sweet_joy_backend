<?php


namespace App\Http\Services;

/**
 * Class ProductsForUserService
 * @package App\Http\Services
 */
class ProductsForUserService
{
    private $productInformationService;
    private $pointsOfSaleService;
    private $schedulesService;
    private $itemsService;
    private $categoriesItemService;

    /**
     * ProductsForUserService constructor.
     * @param ProductInformationService $productInformationService
     * @param PointsOfSaleService $pointsOfSaleService
     * @param SchedulesService $schedulesService
     * @param ItemsService $itemsService
     * @param CategoriesItemService $categoriesItemService
     */
    public function __construct(
        ProductInformationService $productInformationService,
        PointsOfSaleService $pointsOfSaleService,
        SchedulesService $schedulesService,
        ItemsService $itemsService,
        CategoriesItemService $categoriesItemService
    ){
        $this->productInformationService = $productInformationService;
        $this->pointsOfSaleService = $pointsOfSaleService;
        $this->schedulesService= $schedulesService;
        $this->itemsService = $itemsService;
        $this->categoriesItemService = $categoriesItemService;
    }

    /**
     * @return array
     */
    public function getProductsForUsers() {
        $result = [
            'products' => CacheService::cacheProductsInfo('get', 'products'),
            'points_of_sale' => CacheService::cacheProductsInfo('get', 'points_of_sale'),
            'schedules' => CacheService::cacheProductsInfo('get', 'schedules'),
            'items' => CacheService::cacheProductsInfo('get', 'items'),
            'categories' => CacheService::cacheProductsInfo('get', 'categories'),
        ];
        if (!isset($result['products'])) {
            $tempProducts = $this->productInformationService->getProductsInfo();
            $preparedProducts = [];
            foreach ($tempProducts as $product) {
                if (!isset($preparedProducts[$product['id_i']])) {
                    $preparedProducts[$product['id_i']] = [];
                }
                array_push($preparedProducts[$product['id_i']], $product);
            }
            $result['products'] = $preparedProducts;
            CacheService::cacheProductsInfo('create', 'products', $result['products']);
        }
        if (!isset($result['points_of_sale'])) {
            $result['points_of_sale'] = $this->pointsOfSaleService->getPointsOfSale();
            CacheService::cacheProductsInfo('create', 'points_of_sale', $result['points_of_sale']);
        }
        if (!isset($result['schedules'])) {
            $result['schedules'] = $this->schedulesService->getSchedules();
            CacheService::cacheProductsInfo('create', 'schedules', $result['schedules']);
        }
        if (!isset($result['items'])) {
            $result['items'] = $this->itemsService->getItems();
            CacheService::cacheProductsInfo('create', 'items', $result['items']);
        }
        if (!isset($result['categories'])) {
            $result['categories'] = $this->categoriesItemService->getCategories();
            CacheService::cacheProductsInfo('create', 'categories', $result['categories']);
        }
        return $result;
    }
}
