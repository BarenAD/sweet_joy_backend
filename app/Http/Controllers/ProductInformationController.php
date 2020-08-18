<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateProductInfo;
use App\Repositories\ProductInformationRepository;
use Illuminate\Http\Request;

class ProductInformationController extends Controller
{
    public function getProductsInfo(Request $request) {
        return response(ProductInformationRepository::getProductsInfo($request->get('id')), 200);
    }

    public function createProductInfo(ChangeOrCreateProductInfo $request) {
        return response(
            ProductInformationRepository::createProductInfo(
                $request->user(),
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
            ProductInformationRepository::changeProductInfo(
                $request->user(),
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
        return response(ProductInformationRepository::deleteProductInfo(
            $request->user(),
            (int) $request->get('id')
        ), 200);
    }
}
