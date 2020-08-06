<?php


namespace App\Repositories;


use App\Models\ProductInformation;

class ProductInformationRepository
{
    public function getProductsInfo(int $id = null) {
        if (isset($id)) {
            return ProductInformation::findOrFail($id);
        }
        return ProductInformation::all();
    }

    public function createProductInfo(
        int $id_i,
        int $id_pos,
        int $price,
        int $count
    ) {
        return ProductInformation::create([
            'price' => $price,
            'count' => $count,
            'id_i' =>  $id_i,
            'id_pos' => $id_pos,
        ]);
    }

    public function changeProductInfo(
        int $id,
        int $id_i,
        int $id_pos,
        int $price,
        int $count
    ) {
        $pointOfSale = ProductInformation::findOrFail($id);
        $pointOfSale->fill([
            'price' => $price,
            'count' => $count,
            'id_i' =>  $id_i,
            'id_pos' => $id_pos,
        ])->save();
        return $pointOfSale;
    }

    public function deleteProductInfo(int $id) {
        return ProductInformation::findOrFail($id)->delete();
    }
}
