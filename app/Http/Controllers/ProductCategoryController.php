<?php


namespace App\Http\Controllers;


use App\Http\Requests\Products\Categories\ProductCategoryIndexRequest;
use App\Repositories\ProductCategoryRepository;

class ProductCategoryController extends Controller
{
    private ProductCategoryRepository $productCategoryRepository;

    public function __construct(
        ProductCategoryRepository $productCategoryRepository
    ){
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function indexWithNotProduct(ProductCategoryIndexRequest $request)
    {
        $response = $this->productCategoryRepository->getAll();
        if ($request->query('groupBy') === 'products') {
            $response = $response->groupBy('product_id');
        } else if ($request->query('groupBy') === 'categories') {
            $response = $response->groupBy('category_id');
        }
        return response($response, 200);
    }
}
