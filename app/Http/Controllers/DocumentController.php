<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\DestroyDocumentRequest;
use App\Http\Requests\Documents\IndexDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Services\DocumentService;
use App\Repositories\DocumentRepository;

/**
 * Class DocumentController
 * @package App\Http\Controllers
 */
class DocumentController extends Controller
{
    private DocumentService $documentsService;
    private DocumentRepository $documentsRepository;

    public function __construct(
        DocumentService $documentsService,
        DocumentRepository $documentsRepository
    ) {
        $this->documentsService = $documentsService;
        $this->documentsRepository = $documentsRepository;
    }

    public function index(IndexDocumentRequest $request)
    {
        return response($this->documentsService->getAll(), 200);
    }

    public function show(IndexDocumentRequest $request, int $id)
    {
        return response($this->documentsService->find($id), 200);
    }

    public function store(StoreDocumentRequest $request)
    {
        return response($this->documentsService->store(
            $request->get('name'),
            $request->file('document')
        ), 200);
    }

    public function update(UpdateDocumentRequest $request, int $id)
    {
        return response($this->documentsRepository->update($id, ['name' => $request->get('name')]), 200);
    }

    public function destroy(DestroyDocumentRequest $request, int $id)
    {
        $this->documentsService->destroy($id);
        return response($id . 'destroyed', 200);
    }

}
