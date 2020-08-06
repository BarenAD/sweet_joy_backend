<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateProductInfo;
use App\Repositories\ProductInformationRepository;
use Illuminate\Http\Request;

class ProductInformationController extends Controller
{
    private $productInformationRepository;

    public function __construct(ProductInformationRepository $productInformationRepository)
    {
        $this->productInformationRepository = $productInformationRepository;
    }

    public function getProductsInfo(Request $request) {
        return response($this->productInformationRepository->getProductsInfo($request->get('id')), 200);
    }

    public function createProductInfo(ChangeOrCreateProductInfo $request) {
        return response(
            $this->productInformationRepository->createProductInfo(
                $request->get('id_i'),
                $request->get('id_pos'),
                $request->get('price'),
                $request->get('count')
            ),
            200
        );
    }

    public function changeProductInfo(ChangeOrCreateProductInfo $request) {
        return response(
            $this->productInformationRepository->changeProductInfo(
                (int) $request->get('id'),
                $request->get('id_i'),
                $request->get('id_pos'),
                $request->get('price'),
                $request->get('count')
            ),
            200
        );
    }

    public function deleteProductInfo(Request $request) {
        return response($this->productInformationRepository->deleteProductInfo((int) $request->get('id')), 200);
    }
}
