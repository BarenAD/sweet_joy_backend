<?php


namespace App\Repositories;


use App\Models\PointOfSale;

/**
 * Class PointOfSaleRepository
 * @package App\Repositories
 */
class PointOfSaleRepository
{
    private PointOfSale $model;

    public function __construct(PointOfSale $pointOfSale)
    {
        $this->model = $pointOfSale;
    }

    public function getPointsOfSale(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function create(
        int $id_schedule,
        string $address,
        string $phone,
        string $map_integration = null
    ) {
        return $this->model::create([
            'id_s' => $id_schedule,
            'address' => $address,
            'phone' => $phone,
            'map_integration' => $map_integration,
        ]);
    }
}
