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
    private $documentsService;

    /**
     * DocumentsController constructor.
     * @param DocumentsService $documentsService
     */
    public function __construct(DocumentsService $documentsService)
    {
        $this->documentsService = $documentsService;
    }

    /**
     * @param Request $request
     * @param int|null $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getDocuments(Request $request, int $id = null)
    {
        return response($this->documentsService->getDocuments($id), 200);
    }

    /**
     * @param CreateDocuments $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public  function createDocument(CreateDocuments $request)
    {
        return response($this->documentsService->createDocument(
            $request->user(),
            $request->get('name'),
            $request->file('document')
        ), 200);
    }

    /**
     * @param ChangeDocuments $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeDocument(ChangeDocuments $request, int $id)
    {
        return response($this->documentsService->changeDocument(
            $request->user(),
            $id,
            $request->get('name')
        ), 200);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteDocument(Request $request, int $id)
    {
        return response($this->documentsService->deleteDocument(
            $request->user(),
            $id
        ), 200);
    }

}
