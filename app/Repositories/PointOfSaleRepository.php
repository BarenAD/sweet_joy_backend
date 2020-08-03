<?php


namespace App\Repositories;


use App\Models\PointOfSale;

class PointOfSaleRepository
{

    public function getPointsOfSale(int $id = null) {
        if (isset($id)) {
            return PointOfSale::findOrFail($id);
        }
        return PointOfSale::all();
    }

    public function createPointOfSale(int $id_s, string $address, string $phone) {
        return PointOfSale::create([
            'id_s' => $id_s,
            'address' => $address,
            'phone' => $phone,
        ]);
    }

    public function changePointOfSale(int $id, int $id_s, string $address, string $phone) {
        $pointOfSale = PointOfSale::findOrFail($id);
        $pointOfSale->fill([
            'id_s' => $id_s,
            'address' => $address,
            'phone' => $phone,
        ])->save();
        return $pointOfSale;
    }

    public function deletePointOfSale(int $id) {
        return PointOfSale::findOrFail($id)->delete();
    }
}
