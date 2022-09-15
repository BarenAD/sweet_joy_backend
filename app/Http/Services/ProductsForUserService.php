<?php


namespace App\Http\Services;

/**
 * Class ProductsForUserService
 * @package App\Http\Services
 */
class ProductsForUserService
{
    private ProductInformationService $productInformationService;
    private PointsOfSaleService $pointsOfSaleService;
    private SchedulesService $schedulesService;
    private ProductsService $itemsService;
    private CategoriesItemService $categoriesItemService;
    private DocumentService $documentsService;
    private LocationsDocumentsService $locationsDocumentsService;
    private SiteConfigurationsService $siteConfigurationsService;

    public function __construct(
        ProductInformationService $productInformationService,
        PointsOfSaleService $pointsOfSaleService,
        SchedulesService $schedulesService,
        ProductsService $itemsService,
        CategoriesItemService $categoriesItemService,
        DocumentService $documentsService,
        LocationsDocumentsService $locationsDocumentsService,
        SiteConfigurationsService $siteConfigurationsService
    ){
        $this->productInformationService = $productInformationService;
        $this->pointsOfSaleService = $pointsOfSaleService;
        $this->schedulesService= $schedulesService;
        $this->itemsService = $itemsService;
        $this->categoriesItemService = $categoriesItemService;
        $this->documentsService = $documentsService;
        $this->locationsDocumentsService = $locationsDocumentsService;
        $this->siteConfigurationsService = $siteConfigurationsService;
    }

    public function getProductsForUsers()
    {
        $result = [
            'products' => CacheService::cacheProductsInfo('get', 'products'),
            'points_of_sale' => CacheService::cacheProductsInfo('get', 'points_of_sale'),
            'schedules' => CacheService::cacheProductsInfo('get', 'schedules'),
            'items' => CacheService::cacheProductsInfo('get', 'items'),
            'categories' => CacheService::cacheProductsInfo('get', 'categories'),
            'documents' => CacheService::cacheProductsInfo('get', 'documents'),
            'locations_documents' => CacheService::cacheProductsInfo('get', 'locations_documents'),
            'site_configurations' => CacheService::cacheProductsInfo('get', 'site_configurations'),
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
        if (!isset($result['documents'])) {
            $result['documents'] = $this->documentsService->getAll();
            CacheService::cacheProductsInfo('create', 'documents', $result['documents']);
        }
        if (!isset($result['locations_documents'])) {
            $result['locations_documents'] = $this->locationsDocumentsService->getLocationsDocuments();
            CacheService::cacheProductsInfo('create', 'locations_documents', $result['locations_documents']);
        }
        if (!isset($result['site_configurations'])) {
            $result['site_configurations'] = $this->siteConfigurationsService->getSiteConfigurations();
            CacheService::cacheProductsInfo('create', 'site_configurations', $result['site_configurations']);
        }
        return $result;
    }
}
