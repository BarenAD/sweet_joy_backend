<?php


namespace App\Repositories;


use App\Models\Document;

/**
 * Class DocumentRepository
 * @package App\Repositories
 */
class DocumentRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Document::class;
    }
}
