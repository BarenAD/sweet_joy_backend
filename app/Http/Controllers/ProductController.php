<?php

namespace App\Http\Controllers;

use App\DTO\ProductDTO;
use App\Http\Requests\Products\ProductDestroyRequest;
use App\Http\Requests\Products\ProductIndexRequest;
use App\Http\Requests\Products\ProductStoreRequest;
use App\Http\Requests\Products\ProductUpdateRequest;
use App\Http\Services\ProductService;

/**
 * Class ProductItemsController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    private ProductService $productsService;

    public function __construct(ProductService $productsService)
    {
        $this->productsService = $productsService;
    }

    public function index(ProductIndexRequest $request)
    {
        return response($this->productsService->getAll(
            boolval($request->query('withCategories'))
        ), 200);
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
