<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;

use App\Exceptions\BaseException;
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
    private string $pathToDocuments;

    public function __construct(
        DocumentRepository $documentsRepository
    ){
        $this->documentsRepository = $documentsRepository;
        $this->pathToDocuments = config('filesystems.path_inside_disk.documents');
    }

    public function getAll(): array
    {
        $documents = $this->documentsRepository->getAll();
        $result = [];
        foreach ($documents as $document) {
            $result[] = [
                'id' => $document->id,
                'name' => $document->name,
                'url' => Storage::disk('public')->url($this->pathToDocuments.$document->urn)
            ];
        }
        return $result;
    }

    public function find($id): array
    {
        $document = $this->documentsRepository->find($id);
        return [
            'name' => $document->name,
            'url' => Storage::disk('public')->url($this->pathToDocuments.$document->urn)
        ];
    }

    public function store(string $name, UploadedFile $document): Model
    {
        $documentName = uniqid('document_').'.'.$document->getClientOriginalExtension();
        try {
            Storage::disk('public')->putFileAs($this->pathToDocuments, $document, $documentName);
            return $this->documentsRepository->store([
                'name' => $name,
                'urn' => $documentName
            ]);
        } catch (QueryException $exception) {
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
            Storage::disk('public')->delete($this->pathToDocuments . $document->urn);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new BaseException('file_is_not_destroy', $exception);
        }
    }
}
