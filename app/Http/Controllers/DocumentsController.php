<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\ChangeDocuments;
use App\Http\Requests\Documents\CreateDocuments;
use App\Http\Services\DocumentsService;
use Illuminate\Http\Request;

/**
 * Class DocumentsController
 * @package App\Http\Controllers
 */
class DocumentsController extends Controller
{
    private DocumentsService $documentsService;

    public function __construct(DocumentsService $documentsService)
    {
        $this->documentsService = $documentsService;
    }

    public function getDocuments(Request $request, int $id = null)
    {
        return response($this->documentsService->getDocuments($id), 200);
    }

    public  function createDocument(CreateDocuments $request)
    {
        return response($this->documentsService->createDocument(
            $request->user(),
            $request->get('name'),
            $request->file('document')
        ), 200);
    }

    public function changeDocument(ChangeDocuments $request, int $id)
    {
        return response($this->documentsService->changeDocument(
            $request->user(),
            $id,
            $request->get('name')
        ), 200);
    }

    public function deleteDocument(Request $request, int $id)
    {
        return response($this->documentsService->deleteDocument(
            $request->user(),
            $id
        ), 200);
    }

}
