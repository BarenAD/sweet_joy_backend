<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateDocuments;
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
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getDocuments(Request $request)
    {
        return response($this->documentsService->getDocuments($request->get('id')), 200);
    }

    /**
     * @param ChangeOrCreateDocuments $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public  function createDocument(ChangeOrCreateDocuments $request)
    {
        try {
            return response($this->documentsService->createDocument(
                $request->user(),
                $request->get('name'),
                $request->file('document')
            ), 200);
        } catch (\Exception $e) {
        }
    }

    /**
     * @param ChangeOrCreateDocuments $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeDocument(ChangeOrCreateDocuments $request)
    {
        return response($this->documentsService->changeDocument(
            $request->user(),
            (int) $request->get('id'),
            $request->get('name')
        ), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteDocument(Request $request)
    {
        return response($this->documentsService->deleteDocument(
            $request->user(),
            (int) $request->get('id')
        ), 200);
    }

}
