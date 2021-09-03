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
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class DocumentsService
 * @package App\Http\Services
 */
class DocumentsService
{
    private $documentsRepository;
    private $pathForDocuments;
    private $pathPublicToStorage;
    private $pathForStorage;

    /**
     * @param $path
     * @return mixed
     */
    private function extractNameFromPath($path) {
        $explode = explode("/", $path);
        return end($explode);
    }

    /**
     * DocumentsService constructor.
     * @param DocumentsRepository $documentsRepository
     */
    public function __construct(DocumentsRepository $documentsRepository)
    {
        $this->documentsRepository = $documentsRepository;
        $this->pathForDocuments = config('storage.path_for_documents');
        $this->pathPublicToStorage = config('storage.path_for_storage');
        $this->pathForStorage = config('storage.path_for_storage_for_storage_service');
    }

    /**
     * @param int|null $id
     * @return \App\Models\Documents[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDocuments(int $id = null)
    {
        return $this->documentsRepository->getDocuments($id);
    }

    /**
     * @param User $user
     * @param string $name
     * @param File $document
     * @return mixed
     * @throws \Exception
     */
    public function createDocument(User $user, string $name, $document)
    {
        if (DocumentsPolicy::canCreate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @param string $name
     * @return \App\Models\Documents[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeDocument(User $user, int $id, string $name)
    {
        if (DocumentsPolicy::canUpdate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteDocument(User $user, int $id)
    {
        if (DocumentsPolicy::canDelete($user)) {
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
