<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateItem;
use App\Http\Services\ItemsService;
use Illuminate\Http\Request;

/**
 * Class ProductItemsController
 * @package App\Http\Controllers
 */
class ProductItemsController extends Controller
{
    private $itemsService;

    /**
     * ProductItemsController constructor.
     * @param ItemsService $itemsService
     */
    public function __construct(ItemsService $itemsService)
    {
        $this->itemsService = $itemsService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getItems(Request $request)
    {
        return response($this->itemsService->getItems($request->get('id')), 200);
    }

    /**
     * @param ChangeOrCreateItem $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createItem(ChangeOrCreateItem $request)
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

    /**
     * @param ChangeOrCreateItem $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeItem(ChangeOrCreateItem $request)
    {
        try {
            return response(
                $this->itemsService->changeItem(
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
        } catch (\Exception $e) {
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteItem(Request $request)
    {
        return response($this->itemsService->deleteItem($request->user(), $request->get('id')), 200);
    }
}
