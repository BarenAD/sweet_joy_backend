<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 20.08.21
 * Time: 16:00
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\LocationsDocumentsPolicy;
use App\Repositories\LocationsDocumentsRepository;

/**
 * Class LocationsDocumentsService
 * @package App\Http\Services
 */
class LocationsDocumentsService
{
    private LocationsDocumentsRepository $locationsDocumentsRepository;
    private LocationsDocumentsPolicy $locationsDocumentsPolicy;

    public function __construct(
        LocationsDocumentsRepository $locationsDocumentsRepository,
        LocationsDocumentsPolicy $locationsDocumentsPolicy
    ){
        $this->locationsDocumentsRepository = $locationsDocumentsRepository;
        $this->locationsDocumentsPolicy = $locationsDocumentsPolicy;
    }

    public function getLocationsDocuments(int $id = null)
    {
        return $this->locationsDocumentsRepository->getLocationsDocuments($id);
    }

    public function changeLocationsDocuments(
        User $user,
        int $id,
        int $id_document = null
    ) {
        $locationDocument = $this->locationsDocumentsRepository->getLocationsDocuments($id);
        if ($this->locationsDocumentsPolicy->canUpdate($user)) {
            $locationDocument->fill([
                'id_d' => $id_document,
            ])->save();
            CacheService::cacheProductsInfo('delete', 'locations_documents');
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
        return $locationDocument;
    }
}
