<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Documents\UpdateDocument;
use App\Http\Requests\Documents\StoreDocument;
use App\Http\Services\DocumentsService;
use App\Repositories\DocumentsRepository;
use Illuminate\Http\Request;

/**
 * Class DocumentsController
 * @package App\Http\Controllers
 */
class DocumentsController extends Controller
{
    private DocumentsService $documentsService;
    private DocumentsRepository $documentsRepository;

    public function __construct(
        DocumentsService $documentsService,
        DocumentsRepository $documentsRepository
    ) {
        $this->documentsService = $documentsService;
        $this->documentsRepository = $documentsRepository;
    }

    public function index()
    {
        return response($this->documentsService->getAll(), 200);
    }

    public function show(Request $request, int $id)
    {
        return response($this->documentsService->get($id), 200);
    }

    public function store(StoreDocument $request)
    {
        try {
            return response($this->documentsService->store(
                $request->get('name'),
                $request->file('document')
            ), 200);
        } catch (\Exception $exception) {
            throw new BaseException('file_is_not_stored', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);
        }
    }

    public function update(UpdateDocument $request, int $id)
    {
        return response($this->documentsRepository->update($id, ['name' => $request->get('name')]), 200);
    }

    public function destroy(Request $request, int $id)
    {
        try {
            $this->documentsService->destroy($id);
        } catch (\Exception $exception) {
            throw new BaseException('file_is_not_destroy', $exception->getTrace());
        }
        return response(1, 200);
    }

}
