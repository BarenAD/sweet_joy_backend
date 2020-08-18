<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreatePointOfSale;
use App\Repositories\PointOfSaleRepository;
use Illuminate\Http\Request;

class PointsOfSaleController extends Controller
{
    public function getPoints(Request $request) {
        return response(PointOfSaleRepository::getPointsOfSale($request->get('id')), 200);
    }

    public function createPoints(ChangeOrCreatePointOfSale $request) {
        return response(
            PointOfSaleRepository::createPointOfSale(
                $request->user(),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    public function changePoints(ChangeOrCreatePointOfSale $request) {
        return response(
            PointOfSaleRepository::changePointOfSale(
                $request->user(),
                $request->get('id'),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    public function deletePoints(Request $request) {
        return response(PointOfSaleRepository::deletePointOfSale($request->user(), $request->get('id')), 200);
    }
}
