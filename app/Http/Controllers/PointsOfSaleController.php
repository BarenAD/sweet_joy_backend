<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreatePointOfSale;
use App\Http\Services\PointsOfSaleService;
use App\Repositories\PointOfSaleRepository;
use Illuminate\Http\Request;

/**
 * Class PointsOfSaleController
 * @package App\Http\Controllers
 */
class PointsOfSaleController extends Controller
{
    private $pointsOfSaleService;

    /**
     * PointsOfSaleController constructor.
     * @param PointsOfSaleService $pointsOfSaleService
     */
    public function __construct(PointsOfSaleService $pointsOfSaleService)
    {
        $this->pointsOfSaleService = $pointsOfSaleService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getPoints(Request $request) {
        return response($this->pointsOfSaleService->getPointsOfSale($request->get('id')), 200);
    }

    /**
     * @param ChangeOrCreatePointOfSale $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createPoints(ChangeOrCreatePointOfSale $request) {
        return response(
            $this->pointsOfSaleService->createPointOfSale(
                $request->user(),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    /**
     * @param ChangeOrCreatePointOfSale $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changePoints(ChangeOrCreatePointOfSale $request) {
        return response(
            $this->pointsOfSaleService->changePointOfSale(
                $request->user(),
                $request->get('id'),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone')
            ),
            200
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deletePoints(Request $request) {
        return response($this->pointsOfSaleService->deletePointOfSale($request->user(), $request->get('id')), 200);
    }
}
