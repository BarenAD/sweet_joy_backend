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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getLocationsDocuments(Request $request)
    {
        return response($this->locationsDocumentsService->getLocationsDocuments($request->get('id')), 200);
    }

    /**
     * @param ChangeLocationsDocuments $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeLocationsDocuments(ChangeLocationsDocuments $request) {
        return response($this->locationsDocumentsService->changeLocationsDocuments(
            $request->user(),
            (int) $request->get('id'),
            (int) $request->get('id_d')
        ), 200);
    }
}
