<?php


namespace Tests\Feature;


use App\DTO\ParentModelDTO;
use App\Http\Requests\Documents\Locations\UpdateDocumentLocationRequest;
use App\Models\Document;
use App\Models\DocumentLocation;
use Tests\TestApiResource;

class DocumentLocationTest extends TestApiResource
{
    protected function setUpProperties()
    {
        $this->baseRouteName = 'management.documents.locations';
        $this->model = new DocumentLocation();
        $this->only = ['index', 'show', 'update'];
        $this->formRequests = [
            'update' => UpdateDocumentLocationRequest::class,
        ];
        $this->parentModelDTOs = [
            ParentModelDTO::make([
                'model' => new Document(),
                'foreignKey' => 'document_id',
            ]),
        ];
    }
}
