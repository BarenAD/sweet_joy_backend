<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\Locations\IndexDocumentLocationRequest;
use App\Http\Requests\Documents\Locations\UpdateDocumentLocationRequest;
use App\Repositories\DocumentLocationRepository;

/**
 * Class LocationsDocumentsController
 * @package App\Http\Controllers
 */
class DocumentLocationController extends Controller
{
    private DocumentLocationRepository $documentLocationRepository;

    public function __construct(DocumentLocationRepository $documentLocationRepository)
    {
        $this->documentLocationRepository = $documentLocationRepository;
    }

    public function index(IndexDocumentLocationRequest $request)
    {
        return response($this->documentLocationRepository->getAll(), 200);
    }

    public function show(IndexDocumentLocationRequest $request, int $id)
    {
        return response($this->documentLocationRepository->find($id), 200);
    }

    public function update(UpdateDocumentLocationRequest $request, int $id)
    {
        return response($this->documentLocationRepository->update($id, $request->validated()), 200);
    }
}
