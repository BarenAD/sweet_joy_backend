<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CategoryDestroyRequest;
use App\Http\Requests\Categories\CategoryIndexRequest;
use App\Http\Requests\Categories\CategoryStoreRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Http\Utils\UserPermissionUtil;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    private CategoryRepository $categoriesRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        CategoryRepository $categoriesRepository,
        UserPermissionUtil $userPermissionUtil
    ) {
        $this->categoriesRepository = $categoriesRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function index(Request $request): Response
    {
        $result = null;
        $withCache = boolval($request->query('withCache'));
        $cacheKey = 'cache_categories';
        if (
            $withCache ||
            is_null($request->user()) ||
            count($this->userPermissionUtil->getUserPermissions($request->user()->id)) === 0
        ) {
            $result = Cache::tags(['main_data', 'categories'])->get($cacheKey, null);
        }
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $this->categoriesRepository->getAll()->toArray();
        Cache::tags(['main_data', 'categories'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }

    public function show(CategoryIndexRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->find($id), 200);
    }

    public function store(CategoryStoreRequest $request): Response
    {
        return response($this->categoriesRepository->store($request->validated()), 200);
    }

    public function update(CategoryUpdateRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->update($id, $request->validated()), 200);
    }

    public function destroy(CategoryDestroyRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->destroy($id), 200);
    }

}
