<?php


namespace App\Repositories;


use App\Models\ProductInformation;

/**
 * Class ProductInformationRepository
 * @package App\Repositories
 */
class ProductInformationRepository
{
    private $model;

    /**
     * ProductInformationRepository constructor.
     * @param ProductInformation $productInformation
     */
    public function __construct(ProductInformation $productInformation)
    {
        $this->model = $productInformation;
    }

    /**
     * @param int|null $id
     * @return ProductInformation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getProductsInformation(int $id = null)
    {
        if (isset($id)) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param int $price
     * @param int $count
     * @param int $id_item
     * @param int $id_point_of_Sale
     * @return mixed
     */
    public function create(int $price, int $count, int $id_item, int $id_point_of_Sale)
    {
        return $this->model::create([
            'price' => $price,
            'count' => $count,
            'id_i' =>  $id_item,
            'id_pos' => $id_point_of_Sale,
        ]);
    }
}
