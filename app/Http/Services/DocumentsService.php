<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;



use App\Models\User;
use App\Policies\DocumentsPolicy;
use App\Repositories\DocumentsRepository;
use Illuminate\Support\Facades\Storage;

/**
 * Class DocumentsService
 * @package App\Http\Services
 */
class DocumentsService
{
    private DocumentsRepository $documentsRepository;
    private DocumentsPolicy $documentsPolicy;
    private string $pathForDocuments;
    private string $pathPublicToStorage;
    private string $pathForStorage;

    private function extractNameFromPath($path)
    {
        $explode = explode("/", $path);
        return end($explode);
    }

    public function __construct(
        DocumentsRepository $documentsRepository,
        DocumentsPolicy $documentsPolicy
    ){
        $this->documentsRepository = $documentsRepository;
        $this->documentsPolicy = $documentsPolicy;
        $this->pathForDocuments = config('storage.path_for_documents');
        $this->pathPublicToStorage = config('storage.path_for_storage');
        $this->pathForStorage = config('storage.path_for_storage_for_storage_service');
    }

    public function getDocuments(int $id = null)
    {
        return $this->documentsRepository->getDocuments($id);
    }

    public function createDocument(User $user, string $name, $document)
    {
        if ($this->documentsPolicy->canCreate($user)) {
                $pathForStorage = $this->pathForStorage . $this->pathForDocuments;
                $pathFileInStorage = Storage::put($pathForStorage, $document);
                $pathForUri = $this->pathPublicToStorage . $this->pathForDocuments . $this->extractNameFromPath($pathFileInStorage);;
            try {
                CacheService::cacheProductsInfo('delete', 'documents');
                return $this->documentsRepository->create($name, $pathForUri);
            } catch (\Exception $exception) {
                Storage::delete($pathFileInStorage);
                GeneratedAborting::internalServerError($exception);
            }
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeDocument(User $user, int $id, string $name)
    {
        if ($this->documentsPolicy->canUpdate($user)) {
            try {
                $document = $this->documentsRepository->getDocuments($id);
                $document->fill([
                    'name' => $name
                ])->save();
                return $document;
            } catch (\Exception $exception) {
                GeneratedAborting::internalServerErrorCustomMessage('Документ с таким именем уже существует');
            }
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function deleteDocument(User $user, int $id)
    {
        if ($this->documentsPolicy->canDelete($user)) {
            $document = $this->documentsRepository->getDocuments($id);
            $pathForStorage = $this->pathForStorage . $this->pathForDocuments . $this->extractNameFromPath($document->uri);
            Storage::delete($pathForStorage);
            CacheService::cacheProductsInfo('delete', 'documents');
            return $document->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
