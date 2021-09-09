<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateProductInfo;
use App\Http\Services\ProductInformationService;
use App\Http\Services\ProductsForUserService;
use Illuminate\Http\Request;

/**
 * Class ProductInformationController
 * @package App\Http\Controllers
 */
class ProductInformationController extends Controller
{
    private $productInformationService;
    private $productsForUserService;

    /**
     * ProductInformationController constructor.
     * @param ProductInformationService $productInformationService
     * @param ProductsForUserService $productsForUserService
     */
    public function __construct(
        ProductInformationService $productInformationService,
        ProductsForUserService $productsForUserService
    ){
        $this->productInformationService = $productInformationService;
        $this->productsForUserService = $productsForUserService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getProductsForUsers(Request $request)
    {
        return response($this->productsForUserService->getProductsForUsers(), 200);
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getProductsInfo(Request $request, int $id = null)
    {
        return response($this->productInformationService->getProductsInfo($id), 200);
    }

    /**
     * @param ChangeOrCreateProductInfo $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createProductInfo(ChangeOrCreateProductInfo $request)
    {
        return response(
            $this->productInformationService->createProductInfo(
                $request->user(),
                $request->get('id_i'),
                $request->get('id_pos'),
                $request->get('price'),
                $request->get('count')
            ),
            200
        );
    }

    /**
     * @param ChangeOrCreateProductInfo $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeProductInfo(ChangeOrCreateProductInfo $request, int $id)
    {
        return response(
            $this->productInformationService->changeProductInfo(
                $request->user(),
                $id,
                $request->get('id_i'),
                $request->get('id_pos'),
                $request->get('price'),
                $request->get('count')
            ),
            200
        );
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteProductInfo(Request $request, int $id)
    {
        return response($this->productInformationService->deleteProductInfo(
            $request->user(),
            $id
        ), 200);
    }
}
