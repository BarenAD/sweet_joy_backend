<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreatePointOfSale;
use App\Repositories\PointOfSaleRepository;
use Illuminate\Http\Request;

class PointsOfSaleController extends Controller
{
    private $pointOfSaleRepository;

    public function __construct(PointOfSaleRepository $pointOfSaleRepository)
    {
        $this->pointOfSaleRepository = $pointOfSaleRepository;
    }

    public function getPoints(Request $request) {
        return response($this->pointOfSaleRepository->getPointsOfSale($request->get('id')), 200);
    }

    public function createPoints(ChangeOrCreatePointOfSale $request) {
        return response(
            $this->pointOfSaleRepository->createPointOfSale(
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    public function changePoints(ChangeOrCreatePointOfSale $request) {
        return response(
            $this->pointOfSaleRepository->changePointOfSale(
                $request->get('id'),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    public function deletePoints(Request $request) {
        return response($this->pointOfSaleRepository->deletePointOfSale($request->get('id')), 200);
    }
}
