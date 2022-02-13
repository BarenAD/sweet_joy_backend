<?php


namespace App\Repositories;


use App\Models\Document;

/**
 * Class DocumentsRepository
 * @package App\Repositories
 */
class DocumentsRepository extends CoreRepository
{
    public function getModelClass(): string
    {
        return Document::class;
    }
}
