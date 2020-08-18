<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateItem;
use App\Repositories\ItemsRepository;
use Illuminate\Http\Request;

class ProductItemsController extends Controller
{
    public function getItems(Request $request) {
        return response(ItemsRepository::getItems($request->get('id')), 200);
    }

    public function createItem(ChangeOrCreateItem $request) {
        if ($request->hasFile('picture')) {
            return response(
                ItemsRepository::createItem(
                    $request->user(),
                    $request->file('picture'),
                    $request->input('name'),
                    $request->input('composition'),
                    $request->input('manufacturer'),
                    $request->input('description'),
                    $request->input('product_unit'),
                    $request->input('categories_item')
                ),
                200
            );
        }
        return response("image is required",424);
    }

    public function changeItem(ChangeOrCreateItem $request) {
        return response(
            ItemsRepository::changeItem(
                $request->user(),
                $request->get('id'),
                $request->input('name'),
                $request->input('composition'),
                $request->input('manufacturer'),
                $request->input('description'),
                $request->input('product_unit'),
                $request->input('categories_item'),
                $request->file('picture')
            ),
            200
        );
    }

    public function deleteItem(Request $request) {
        return response(ItemsRepository::deleteItem($request->user(), $request->get('id')), 200);
    }
}
