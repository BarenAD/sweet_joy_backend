<?php

namespace App\Http\Controllers;

use App\Http\Requests\Documents\DestroyDocumentRequest;
use App\Http\Requests\Documents\IndexDocumentRequest;
use App\Http\Requests\Documents\UpdateDocumentRequest;
use App\Http\Requests\Documents\StoreDocumentRequest;
use App\Http\Services\DocumentService;
use App\Http\Utils\UserPermissionUtil;
use App\Repositories\DocumentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Class DocumentController
 * @package App\Http\Controllers
 */
class DocumentController extends Controller
{
    private DocumentService $documentService;
    private DocumentRepository $documentRepository;
    private UserPermissionUtil $userPermissionUtil;

    public function __construct(
        DocumentService $documentsService,
        DocumentRepository $documentsRepository,
        UserPermissionUtil $userPermissionUtil
    ) {
        $this->documentService = $documentsService;
        $this->documentRepository = $documentsRepository;
        $this->userPermissionUtil = $userPermissionUtil;
    }

    public function getUsed(Request $request)
    {
        $result = null;
        $cacheKey = 'cache_documents';
        $result = Cache::tags(['main_data', 'documents'])->get($cacheKey, null);
        if (!is_null($result)) {
            return response($result, 200);
        }
        $result = $this->documentService->getAllUsed();
        Cache::tags(['main_data', 'documents'])->put($cacheKey, $result, config('cache.timeout'));
        return response($result, 200);
    }

    public function index(IndexDocumentRequest $request)
    {
        return response($this->documentService->getAll(), 200);
    }

    public function show(IndexDocumentRequest $request, int $id)
    {
        return response($this->documentService->find($id), 200);
    }

    public function store(StoreDocumentRequest $request)
    {
        $result = $this->documentService->store(
            $request->get('name'),
            $request->file('document')
        )->toArray();
        $result['url'] = $this->documentService->getUrlDocument($result['urn']);
        return response($result, 200);
    }

    public function update(UpdateDocumentRequest $request, int $id)
    {
        return response($this->documentRepository->update($id, ['name' => $request->get('name')]), 200);
    }

    public function destroy(DestroyDocumentRequest $request, int $id)
    {
        $this->documentService->destroy($id);
        return response()->json('OK', 200);
    }

}
