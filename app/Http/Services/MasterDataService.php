<?php


namespace App\Http\Services;

use App\Repositories\CategoryRepository;
use App\Repositories\DocumentLocationRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\ShopProductRepository;
use App\Repositories\ShopRepository;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProductsForUserService
 * @package App\Http\Services
 */
class MasterDataService
{
    private ShopProductRepository $shopProductRepository;
    private ShopRepository $shopRepository;
    private ScheduleRepository $scheduleRepository;
    private ProductService $productService;
    private CategoryRepository $categoryRepository;
    private DocumentService $documentService;
    private DocumentLocationRepository $documentLocationRepository;

    public function __construct(
        ShopProductRepository $shopProductRepository,
        ShopRepository $shopRepository,
        ScheduleRepository $scheduleRepository,
        ProductService $productService,
        CategoryRepository $categoryRepository,
        DocumentService $documentService,
        DocumentLocationRepository $documentLocationRepository
    ){
        $this->shopProductRepository = $shopProductRepository;
        $this->shopRepository = $shopRepository;
        $this->scheduleRepository= $scheduleRepository;
        $this->productService = $productService;
        $this->categoryRepository = $categoryRepository;
        $this->documentService = $documentService;
        $this->documentLocationRepository = $documentLocationRepository;
    }

    public function getMasterData()
    {
        return Cache::tags(['products'])->remember('cache_products', 3600, function () {
            return [
                'products' => $this->productService->getAll(),
                'categories' => $this->categoryRepository->getAll(),
                'shops' => $this->shopRepository->getAllWithSchedules(),
                'shop_products' => $this->shopProductRepository->getAllGroupProduct(),
                'documents' => $this->documentService->getAllUsed(),
            ];
        });
    }
}
