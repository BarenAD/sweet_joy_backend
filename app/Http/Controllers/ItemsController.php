<?php

namespace App\Http\Controllers;

use App\Http\Requests\Items\ChangeItem;
use App\Http\Requests\Items\CreateItem;
use App\Http\Services\ItemsService;
use Illuminate\Http\Request;

/**
 * Class ProductItemsController
 * @package App\Http\Controllers
 */
class ItemsController extends Controller
{
    private ItemsService $itemsService;

    public function __construct(ItemsService $itemsService)
    {
        $this->itemsService = $itemsService;
    }

    public function getItems(Request $request, int $id = null)
    {
        return response($this->itemsService->getItems($id), 200);
    }

    public function createItem(CreateItem $request)
    {
        if ($request->hasFile('picture')) {
            try {
                return response(
                    $this->itemsService->createItem(
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
            } catch (\Exception $e) {
            }
        }
        return response("image is required",424);
    }

    public function changeItem(ChangeItem $request, int $id)
    {
        try {
            return response(
                $this->itemsService->changeItem(
                    $request->user(),
                    $id,
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
        } catch (\Exception $e) {
        }
    }

    public function deleteItem(Request $request, int $id)
    {
        return response($this->itemsService->deleteItem($request->user(), $id), 200);
    }
}
