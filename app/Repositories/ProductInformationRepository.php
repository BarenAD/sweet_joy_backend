<?php


namespace App\Repositories;


use App\Models\ProductInformation;
use App\Models\User;
use App\Policies\ProductInformationPolicy;

class ProductInformationRepository
{
    public static function getProductsInfo(int $id = null) {
        if (isset($id)) {
            return ProductInformation::findOrFail($id);
        }
        return ProductInformation::all();
    }

    public static function createProductInfo(
        User $user,
        int $id_i,
        int $id_pos,
        int $price,
        int $count
    ) {
        if (ProductInformationPolicy::canCreate($user, $id_pos)) {
            return ProductInformation::create([
                'price' => $price,
                'count' => $count,
                'id_i' =>  $id_i,
                'id_pos' => $id_pos,
            ]);
        }
        abort(403, 'Недостаточно прав администрирования');
    }

    public static function changeProductInfo(
        User $user,
        int $id,
        int $id_i,
        int $id_pos,
        int $price,
        int $count
    ) {
        $productInformation = ProductInformation::findOrFail($id);
        if (ProductInformationPolicy::canUpdateDelete($user, $productInformation)) {
            $productInformation->fill([
                'price' => $price,
                'count' => $count,
                'id_i' => $id_i,
                'id_pos' => $id_pos,
            ])->save();
            return $productInformation;
        }
        abort(403, 'Недостаточно прав администрирования');
    }

    public static function deleteProductInfo(User $user, int $id) {
        $productInformation = ProductInformation::findOrFail($id);
        if (ProductInformationPolicy::canUpdateDelete($user, $productInformation)) {
            return $productInformation->delete();
        }
        abort(403, 'Недостаточно прав администрирования');
    }
}
