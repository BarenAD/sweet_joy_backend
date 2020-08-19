<?php


namespace App\Http\services;


use App\Repositories\CacheRepository;
use App\Repositories\CategoryItemRepository;
use App\Repositories\ItemsRepository;
use App\Repositories\PointOfSaleRepository;
use App\Repositories\ProductInformationRepository;
use App\Repositories\SchedulesRepository;

class ProductsForUserService
{
    public static function getProductsForUsers() {
        $result = [
            'products' => CacheRepository::cacheProductsInfo('get', 'products'),
            'points_of_sale' => CacheRepository::cacheProductsInfo('get', 'points_of_sale'),
            'schedules' => CacheRepository::cacheProductsInfo('get', 'schedules'),
            'items' => CacheRepository::cacheProductsInfo('get', 'items'),
            'categories' => CacheRepository::cacheProductsInfo('get', 'categories'),
        ];
        if (!isset($result['products'])) {
            $result['products'] = ProductInformationRepository::getProductsInfo();
            CacheRepository::cacheProductsInfo('create', 'products', $result['products']);
        }
        if (!isset($result['points_of_sale'])) {
            $result['points_of_sale'] = PointOfSaleRepository::getPointsOfSale();
            CacheRepository::cacheProductsInfo('create', 'points_of_sale', $result['points_of_sale']);
        }
        if (!isset($result['schedules'])) {
            $result['schedules'] = SchedulesRepository::getSchedules();
            CacheRepository::cacheProductsInfo('create', 'schedules', $result['schedules']);
        }
        if (!isset($result['items'])) {
            $result['items'] = ItemsRepository::getItems();
            CacheRepository::cacheProductsInfo('create', 'items', $result['items']);
        }
        if (!isset($result['categories'])) {
            $result['categories'] = CategoryItemRepository::getCategories();
            CacheRepository::cacheProductsInfo('create', 'categories', $result['categories']);
        }
        return $result;
    }
}
