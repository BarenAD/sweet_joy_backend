<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeLocationsDocuments;
use App\Http\Services\LocationsDocumentsService;
use Illuminate\Http\Request;

/**
 * Class LocationsDocumentsController
 * @package App\Http\Controllers
 */
class LocationsDocumentsController extends Controller
{
    private $locationsDocumentsService;

    /**
     * LocationsDocumentsController constructor.
     * @param LocationsDocumentsService $locationsDocumentsService
     */
    public function __construct(LocationsDocumentsService $locationsDocumentsService)
    {
        $this->locationsDocumentsService = $locationsDocumentsService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getLocationsDocuments(Request $request, int $id = null)
    {
        return response($this->locationsDocumentsService->getLocationsDocuments($id), 200);
    }

    /**
     * @param ChangeLocationsDocuments $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeLocationsDocuments(ChangeLocationsDocuments $request, int $id)
    {
        return response($this->locationsDocumentsService->changeLocationsDocuments(
            $request->user(),
            $id,
            (int) $request->get('id_d')
        ), 200);
    }
}
