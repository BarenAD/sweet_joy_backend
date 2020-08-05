<?php


namespace App\Repositories;



use App\Models\InformationCategorieItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemsRepository
{
    public function getItems(int $id = null) {
        if (isset($id)) {
            return Item::findOrFail($id);
        }
        return Item::all();
    }

    public function createItem(
        $picture,
        string $name,
        string $composition,
        string $manufacturer,
        string $description,
        string $product_unit,
        array $categories_item
    ) {
        $PicturesItemsRepository = new PicturesItemsRepository();
        $newImage = $PicturesItemsRepository->savePictureForItem($name, $picture);
        try {
            return DB::transaction(function () use (
                $name,
                $composition,
                $manufacturer,
                $description,
                $product_unit,
                $categories_item,
                $newImage
            ) {
                $newItem = Item::create([
                    'picture' => $newImage['fullPathForPicture'],
                    'miniature_picture' => $newImage['fullPathForMiniPicture'],
                    'name' => $name,
                    'composition' => $composition,
                    'manufacturer' => $manufacturer,
                    'description' => $description,
                    'product_unit' => $product_unit
                ]);
                $resultCategories = [];
                foreach ($categories_item as $category) {
                    array_push($resultCategories, InformationCategorieItem::create([
                        'id_i' => $newItem->id,
                        'id_ci' => $category
                    ])->id_ci);
                }
                DB::commit();
                return [
                    'item' => $newItem,
                    'categories' => $resultCategories
                ];
            });
        } catch (\Exception $error) {
            $PicturesItemsRepository->deletePictureForItem($newImage['name']);
            throw new $error;
        }
    }

    public function changeItem(
        int $id,
        $picture = null,
        string $name,
        string $composition,
        string $manufacturer,
        string $description,
        string $product_unit,
        array $categories_item
    ) {
        $item = Item::findOrFail($id);
//        $category->fill([
//            'name' => $name
//        ])->save();
//        return $category;
    }

    public function deleteItem(int $id) {
        $PicturesItemsRepository = new PicturesItemsRepository();
        $item = Item::findOrFail($id);
        $explode = explode("/", $item->picture);
        $PicturesItemsRepository->deletePictureForItem(end($explode));
        return Item::findOrFail($id)->delete();
    }
}
