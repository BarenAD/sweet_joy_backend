<?php


namespace App\Repositories;


use App\Models\ProductInformation;

/**
 * Class ProductInformationRepository
 * @package App\Repositories
 */
class ProductInformationRepository
{
    private ProductInformation $model;

    public function __construct(ProductInformation $productInformation)
    {
        $this->model = $productInformation;
    }

    public function getProductsInformation(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function create(
        int $id_item,
        int $id_point_of_Sale,
        int $price = null,
        int $count = null
    ){
        return $this->model::create([
            'price' => $price,
            'count' => $count,
            'id_i' =>  $id_item,
            'id_pos' => $id_point_of_Sale,
        ]);
    }
}
