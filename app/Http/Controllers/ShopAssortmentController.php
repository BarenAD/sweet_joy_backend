<?php

namespace App\Http\Controllers;

use App\Exceptions\NoReportException;
use App\Http\Requests\ShopAssortment\DestroyShopAssortmentRequest;
use App\Http\Requests\ShopAssortment\IndexShopAssortmentRequest;
use App\Http\Requests\ShopAssortment\StoreShopAssortmentRequest;
use App\Http\Requests\ShopAssortment\UpdateShopAssortmentRequest;
use App\Repositories\ShopAssortmentRepository;

class ShopAssortmentController extends Controller
{
    private ShopAssortmentRepository $shopAssortmentRepository;

    public function __construct(
        ShopAssortmentRepository $shopAssortmentRepository
    ){
        $this->shopAssortmentRepository = $shopAssortmentRepository;
    }

    public function index(IndexShopAssortmentRequest $request)
    {
        return response($this->shopAssortmentRepository->getAll(), 200);
    }

    public function show(IndexShopAssortmentRequest $request, int $id)
    {
        return response($this->shopAssortmentRepository->find($id), 200);
    }

    public function store(StoreShopAssortmentRequest $request)
    {
        $params = $request->validated();
        if ($this->shopAssortmentRepository->hasProductInShop($params['shop_id'], $params['product_id'])) {
            throw new NoReportException('product_already_in_shop');
        }
        return response($this->shopAssortmentRepository->store($params), 200);
    }

    public function update(UpdateShopAssortmentRequest $request, int $id)
    {
        return response($this->shopAssortmentRepository->update($id, $request->validated()), 200);
    }

    public function destroy(DestroyShopAssortmentRequest $request, int $id)
    {
        return response($this->shopAssortmentRepository->destroy($id), 200);
    }
}
