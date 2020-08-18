<?php


namespace App\Repositories;


use App\Http\services\GeneratedAborting;
use App\Models\PointOfSale;
use App\Models\User;
use App\Policies\PointOfSalePolicy;

class PointOfSaleRepository
{

    public static function getPointsOfSale(int $id = null) {
        if (isset($id)) {
            return PointOfSale::findOrFail($id);
        }
        return PointOfSale::all();
    }

    public static function createPointOfSale(User $user, int $id_s, string $address, string $phone) {
        if (PointOfSalePolicy::canCreate($user)) {
            return PointOfSale::create([
                'id_s' => $id_s,
                'address' => $address,
                'phone' => $phone,
            ]);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public static function changePointOfSale(User $user, int $id, int $id_s, string $address, string $phone) {
        $pointOfSale = PointOfSale::findOrFail($id);
        if (PointOfSalePolicy::canUpdate($user, $pointOfSale)) {
            $pointOfSale->fill([
                'id_s' => $id_s,
                'address' => $address,
                'phone' => $phone,
            ])->save();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return $pointOfSale;
    }

    public static function deletePointOfSale(User $user, int $id) {
        $pointOfSale = PointOfSale::findOrFail($id);
        if (PointOfSalePolicy::canDelete($user)) {
            return $pointOfSale->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
