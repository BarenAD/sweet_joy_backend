<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\Shops\Products\DestroyShopProductRequest;
use App\Http\Requests\Shops\Products\IndexShopProductRequest;
use App\Http\Requests\Shops\Products\StoreShopProductRequest;
use App\Http\Requests\Shops\Products\UpdateShopProductRequest;
use App\Http\Utils\UserPermissionUtil;
use App\Repositories\ShopProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShopProductController extends Controller
{
    private ShopProductRepository $shopProductRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        ShopProductRepository $shopProductRepository,
        UserPermissionUtil $userPermissionUtil
    ){
        $this->shopProductRepository = $shopProductRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function getAll(Request $request)
    {
        $result = null;
        $withCache = boolval($request->query('withCache'));
        $groupBy = $request->query('groupBy') ?? null;
        $cacheKey = 'cache_shop_products';
        if ($groupBy) {
            $cacheKey = $cacheKey .'_group_'. $groupBy;
        }
        if (
            $withCache ||
            is_null($request->user()) ||
            count($this->userPermissionUtil->getUserPermissions($request->user()->id)) === 0
        ) {
            $result = Cache::tags(['main_data', 'shop_products'])->get($cacheKey, null);
        }
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $this->shopProductRepository->getAll();
        if ($groupBy === 'products') {
            $result = $result->groupBy('product_id');
        } else if ($groupBy === 'shops') {
            $result = $result->groupBy('shop_id');
        }
        $result = $result->toArray();
        Cache::tags(['main_data', 'shop_products'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }

    public function index(IndexShopProductRequest $request, int $shopId)
    {
        return response($this->shopProductRepository->getShopProducts($shopId), 200);
    }

    public function show(IndexShopProductRequest $request, int $shopId, int $id)
    {
        return response($this->shopProductRepository->findByShop($shopId, $id), 200);
    }

    public function store(StoreShopProductRequest $request, int $shopId)
    {
        $params = $request->validated();
        $params['shop_id'] = $shopId;
        try {
            return response($this->shopProductRepository->store($params), 200);
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->getCode() == 23000) {
                throw new NoReportException('product_already_in_shop');
            }
            throw $exception;
        }
    }

    public function update(UpdateShopProductRequest $request, int $shopId, int $id)
    {
        return response($this->shopProductRepository->updateByShop($shopId, $id, $request->validated()), 200);
    }

    public function destroy(DestroyShopProductRequest $request, int $shopId, int $id)
    {
        return response($this->shopProductRepository->destroyByShop($shopId, $id), 200);
    }
}
