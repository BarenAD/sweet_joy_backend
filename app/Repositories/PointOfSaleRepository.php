<?php


namespace App\Repositories;


use App\Models\PointOfSale;

/**
 * Class PointOfSaleRepository
 * @package App\Repositories
 */
class PointOfSaleRepository
{
    private $model;

    /**
     * PointOfSaleRepository constructor.
     * @param PointOfSale $pointOfSale
     */
    public function __construct(PointOfSale $pointOfSale)
    {
        $this->model = $pointOfSale;
    }

    /**
     * @param int|null $id
     * @return PointOfSale[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPointsOfSale(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param int $id_schedule
     * @param string $address
     * @param string $phone
     * @return mixed
     */
    public function create(int $id_schedule, string $address, string $phone)
    {
        return $this->model::create([
            'id_s' => $id_schedule,
            'address' => $address,
            'phone' => $phone,
        ]);
    }
}
