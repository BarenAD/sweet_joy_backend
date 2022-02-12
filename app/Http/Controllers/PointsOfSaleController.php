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

    public function __construct(PointsOfSaleService $pointsOfSaleService)
    {
        $this->pointsOfSaleService = $pointsOfSaleService;
    }

    public function getPoints(Request $request, int $id = null)
    {
        return response($this->pointsOfSaleService->getPointsOfSale($id), 200);
    }

    public function createPoints(ChangeOrCreatePointOfSale $request)
    {
        return response(
            $this->pointsOfSaleService->createPointOfSale(
                $request->user(),
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone'),
                $request->get('map_integration')
            ),
            200
        );
    }

    public function changePoints(ChangeOrCreatePointOfSale $request, int $id)
    {
        return response(
            $this->pointsOfSaleService->changePointOfSale(
                $request->user(),
                $id,
                $request->get('id_s'),
                $request->get('address'),
                $request->get('phone'),
                $request->get('map_integration')
            ),
            200
        );
    }

    public function deletePoints(Request $request, int $id)
    {
        return response($this->pointsOfSaleService->deletePointOfSale($request->user(), $id), 200);
    }
}
