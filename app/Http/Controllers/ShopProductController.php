<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\Shops\Products\DestroyShopProductRequest;
use App\Http\Requests\Shops\Products\IndexShopProductRequest;
use App\Http\Requests\Shops\Products\StoreShopProductRequest;
use App\Http\Requests\Shops\Products\UpdateShopProductRequest;
use App\Repositories\ShopProductRepository;

class ShopProductController extends Controller
{
    private ShopProductRepository $shopProductRepository;

    public function __construct(
        ShopProductRepository $shopProductRepository
    ){
        $this->shopProductRepository = $shopProductRepository;
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
        if ($this->shopProductRepository->hasProductInShop($shopId, $params['product_id'])) {
            throw new NoReportException('product_already_in_shop');
        }
        return response($this->shopProductRepository->store($params), 200);
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
