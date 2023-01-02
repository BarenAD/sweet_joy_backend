<?php


namespace App\Http\Controllers;


use App\Http\Utils\UserPermissionUtil;
use App\Repositories\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductCategoryController extends Controller
{
    private ProductCategoryRepository $productCategoryRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        ProductCategoryRepository $productCategoryRepository,
        UserPermissionUtil $userPermissionUtil
    ){
        $this->productCategoryRepository = $productCategoryRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function getAll(Request $request)
    {
        $result = null;
        $withCache = boolval($request->query('withCache'));
        $groupBy = $request->query('groupBy') ?? null;
        $cacheKey = 'cache_product_categories';
        if ($groupBy) {
            $cacheKey = $cacheKey .'_group_'. $groupBy;
        }
        if (
            $withCache ||
            is_null($request->user()) ||
            count($this->userPermissionUtil->getUserPermissions($request->user()->id)) === 0
        ) {
            $result = Cache::tags(['main_data', 'product_categories'])->get($cacheKey, null);
        }
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $this->productCategoryRepository->getAll();
        if ($groupBy === 'products') {
            $result = $result->groupBy('product_id');
        } else if ($groupBy === 'categories') {
            $result = $result->groupBy('category_id');
        }
        $result = $result->toArray();
        Cache::tags(['main_data', 'product_categories'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }
}
