<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLocationDocumentRequest;
use App\Http\Services\LocationsDocumentsService;
use Illuminate\Http\Request;

/**
 * Class LocationsDocumentsController
 * @package App\Http\Controllers
 */
class LocationsDocumentsController extends Controller
{
    private LocationsDocumentsService $locationsDocumentsService;

    public function __construct(LocationsDocumentsService $locationsDocumentsService)
    {
        $this->locationsDocumentsService = $locationsDocumentsService;
    }

    public function getLocationsDocuments(Request $request, int $id = null)
    {
        return response($this->locationsDocumentsService->getLocationsDocuments($id), 200);
    }

    public function changeLocationsDocuments(UpdateLocationDocumentRequest $request, int $id)
    {
        return response($this->locationsDocumentsService->changeLocationsDocuments(
            $request->user(),
            $id,
            (int) $request->get('id_d')
        ), 200);
    }
}
