<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;

use App\Exceptions\BaseException;
use App\Repositories\DocumentLocationRepository;
use App\Repositories\DocumentRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Class DocumentService
 * @package App\Http\Services
 */
class DocumentService
{
    private DocumentRepository $documentsRepository;
    private DocumentLocationRepository $documentLocationRepository;
    private string $pathToDocuments;

    public function __construct(
        DocumentRepository $documentsRepository,
        DocumentLocationRepository $documentLocationRepository
    ){
        $this->documentsRepository = $documentsRepository;
        $this->documentLocationRepository = $documentLocationRepository;
        $this->pathToDocuments = config('filesystems.path_inside_disk.documents');
    }

    public function getAllUsed(): array
    {
        $documentLocations = $this->documentLocationRepository->getAllWithDocuments(true)->toArray();
        $result = [];
        foreach ($documentLocations as $documentLocation) {
            $preparedDocument = $documentLocation['document'];
            $preparedDocument['url'] = Storage::disk('public')->url($this->pathToDocuments.$preparedDocument['urn']);
            $preparedDocument['location'] = $documentLocation['identify'];
            unset($preparedDocument['urn']);
            $result[$documentLocation['identify']] = $preparedDocument;
        }
        return $result;
    }

    public function getAll(): array
    {
        $documents = $this->documentsRepository->getAll()->toArray();
        foreach ($documents as &$document) {
            $document['url'] = Storage::disk('public')->url($this->pathToDocuments.$document['urn']);
            unset($document['urn']);
        }
        return $documents;
    }

    public function find($id): array
    {
        $document = $this->documentsRepository->find($id)->toArray();
        $document['url'] = Storage::disk('public')->url($this->pathToDocuments.$document['urn']);
        unset($document['urn']);
        return $document;
    }

    public function store(string $name, UploadedFile $document): Model
    {
        $documentName = uniqid('document_').'.'.$document->getClientOriginalExtension();
        try {
            Storage::disk('public')->putFileAs($this->pathToDocuments, $document, $documentName);
            $newDocument = $this->documentsRepository->store([
                'name' => $name,
                'urn' => $documentName
            ]);
            $newDocument['url'] = Storage::disk('public')->url($this->pathToDocuments.$newDocument['urn']);
            return $newDocument;
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($this->pathToDocuments.$documentName);
            throw new BaseException('file_is_not_stored', $exception);
        }
    }

    public function destroy(int $id): void
    {
        try {
            DB::beginTransaction();
            $document = $this->documentsRepository->find($id);
            $document->delete();
            if (!strpos($this->pathToDocuments . $document->urn, '/demo/')) {
                Storage::disk('public')->delete($this->pathToDocuments . $document->urn);
            }
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new BaseException('file_is_not_destroy', $exception);
        }
    }
}
