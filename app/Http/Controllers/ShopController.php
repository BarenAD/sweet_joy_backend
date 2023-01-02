<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shops\DestroyShopRequest;
use App\Http\Requests\Shops\IndexShopRequest;
use App\Http\Requests\Shops\StoreShopRequest;
use App\Http\Requests\Shops\UpdateShopRequest;
use App\Http\Utils\UserPermissionUtil;
use App\Repositories\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Class ShopController
 * @package App\Http\Controllers
 */
class ShopController extends Controller
{
    private ShopRepository $shopRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        ShopRepository $shopRepository,
        UserPermissionUtil $userPermissionUtil
    ) {
        $this->shopRepository = $shopRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function index(Request $request)
    {
        $result = null;
        $withSchedules = boolval($request->query('withSchedules'));
        $withCache = boolval($request->query('withCache'));
        $cacheKey = 'cache_shops';
        if ($withSchedules) {
            $cacheKey = $cacheKey . '_with_schedules';
        }
        if (
            $withCache ||
            is_null($request->user()) ||
            count($this->userPermissionUtil->getUserPermissions($request->user()->id)) === 0
        ) {
            $result = Cache::tags(['main_data', 'shops'])->get($cacheKey, null);
        }
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $withSchedules ?
            $this->shopRepository->getAllWithSchedules()->toArray()
            :
            $this->shopRepository->getAll()->toArray();
        Cache::tags(['main_data', 'shops'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }

    public function show(IndexShopRequest $request, int $id)
    {
        return response($this->shopRepository->find($id), 200);
    }

    public function store(StoreShopRequest $request)
    {
        return response($this->shopRepository->store($request->validated()), 200);
    }

    public function update(UpdateShopRequest $request, int $id)
    {
        return response($this->shopRepository->update($id, $request->validated()), 200);
    }

    public function destroy(DestroyShopRequest $request, int $id)
    {
        return response($this->shopRepository->destroy($id), 200);
    }
}
