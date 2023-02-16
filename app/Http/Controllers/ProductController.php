<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Http\Requests\Products\ProductDestroyRequest;
use App\Http\Requests\Products\ProductIndexRequest;
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Services\ProductService;
use App\Http\Utils\UserPermissionUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProductItemsController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    private ProductService $productsService;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        ProductService $productsService,
        UserPermissionUtil $userPermissionUtil
    )
    {
        $this->productsService = $productsService;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function index(Request $request)
    {
        $result = null;
        $withCategories = boolval($request->query('withCategories'));
        $withCache = boolval($request->query('withCache'));
        $cacheKey = 'cache_products';
        if ($withCategories) {
            $cacheKey = $cacheKey . '_with_categories';
        }
        if (
            $withCache ||
            is_null($request->user()) ||
            count($this->userPermissionUtil->getUserPermissions($request->user()->id)) === 0
        ) {
            $result = Cache::tags(['main_data', 'products'])->get($cacheKey, null);
        }
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $this->productsService->getAll($withCategories);
        Cache::tags(['main_data', 'products'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }

    public function show(ProductIndexRequest $request, int $id)
    {
        return response($this->productsService->find(
            $id,
            boolval($request->query('withCategories'))
        ), 200);
    }

    public function store(ProductStoreRequest $request)
    {
        $productDTO = ProductDTO::formRequest($request);
        return response($this->productsService->store($productDTO), 200);
    }

    public function update(ProductUpdateRequest $request, int $id)
    {
        $productDTO = ProductDTO::formRequest($request);
        return response($this->productsService->update($id, $productDTO), 200);
    }

    public function destroy(ProductDestroyRequest $request, int $id)
    {
        return response($this->productsService->destroy($id), 200);
    }
}
