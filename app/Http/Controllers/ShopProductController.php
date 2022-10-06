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

    public function index(IndexShopProductRequest $request)
    {
        return response($this->shopProductRepository->getAll(), 200);
    }

    public function show(IndexShopProductRequest $request, int $id)
    {
        return response($this->shopProductRepository->find($id), 200);
    }

    public function store(StoreShopProductRequest $request)
    {
        $params = $request->validated();
        if ($this->shopProductRepository->hasProductInShop($params['shop_id'], $params['product_id'])) {
            throw new NoReportException('product_already_in_shop');
        }
        return response($this->shopProductRepository->store($params), 200);
    }

    public function update(UpdateShopProductRequest $request, int $id)
    {
        return response($this->shopProductRepository->update($id, $request->validated()), 200);
    }

    public function destroy(DestroyShopProductRequest $request, int $id)
    {
        return response($this->shopProductRepository->destroy($id), 200);
    }
}
