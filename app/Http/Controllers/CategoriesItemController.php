<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateCategoryItem;
use App\Http\Services\CategoriesItemService;
use Illuminate\Http\Request;

/**
 * Class ProductCategoriesItemController
 * @package App\Http\Controllers
 */
class CategoriesItemController extends Controller
{
    private $categoriesItemService;

    /**
     * ProductCategoriesItemController constructor.
     * @param CategoriesItemService $categoriesItemService
     */
    public function __construct(CategoriesItemService $categoriesItemService)
    {
        $this->categoriesItemService = $categoriesItemService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getCategories(Request $request, int $id = null)
    {
        return response($this->categoriesItemService->getCategories($id), 200);
    }

    /**
     * @param ChangeOrCreateCategoryItem $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createCategory(ChangeOrCreateCategoryItem $request)
    {
        return response($this->categoriesItemService->createCategory($request->user(), $request->get('name')), 200);
    }

    /**
     * @param ChangeOrCreateCategoryItem $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeCategory(ChangeOrCreateCategoryItem $request, int $id)
    {
        return response($this->categoriesItemService->changeCategory($request->user(), $id, $request->get('name')), 200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteCategory(Request $request, int $id)
    {
        return response($this->categoriesItemService->deleteCategory($request->user(), $id), 200);
    }
}
