<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateItem;
use App\Repositories\ItemsRepository;
use Illuminate\Http\Request;

class ProductItemsController extends Controller
{
    private $itemRepository;

    public function __construct(ItemsRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function getItems(Request $request) {
        return response($this->itemRepository->getItems($request->get('id')), 200);
    }

    public function createItem(ChangeOrCreateItem $request) {
        if ($request->hasFile('picture')) {
            return response(
                $this->itemRepository->createItem(
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
            $this->itemRepository->changeItem(
                $request->get('id'),
                $request->file('picture'),
                $request->get('name'),
                $request->get('composition'),
                $request->get('manufacturer'),
                $request->get('description'),
                $request->get('product_unit'),
                $request->get('categories_item')
            ),
            200
        );
    }

    public function deleteItem(Request $request) {
        return response($this->itemRepository->deleteItem($request->get('id')), 200);
    }
}
