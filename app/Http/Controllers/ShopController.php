<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shops\DestroyShopRequest;
use App\Http\Requests\Shops\IndexShopRequest;
use App\Http\Requests\Shops\StoreShopRequest;
use App\Http\Requests\Shops\UpdateShopRequest;
use App\Repositories\ShopRepository;

/**
 * Class ShopController
 * @package App\Http\Controllers
 */
class ShopController extends Controller
{
    private ShopRepository $shopRepository;

    public function __construct(
        ShopRepository $shopRepository
    ) {
        $this->shopRepository = $shopRepository;
    }

    public function index(IndexShopRequest $request)
    {
        return response($this->shopRepository->getAll(), 200);
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
