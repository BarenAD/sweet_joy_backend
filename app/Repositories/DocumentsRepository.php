<?php


namespace App\Repositories;


use App\Models\Documents;

/**
 * Class DocumentsRepository
 * @package App\Repositories
 */
class DocumentsRepository
{
    private Documents $model;

    public function __construct(Documents $documents)
    {
        $this->model = $documents;
    }

    public function getDocuments(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    public function create(
        string $name,
        string $uri
    ) {
        try {
            return $this->model::create([
                'name' => $name,
                'uri' => $uri
            ]);
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorInfo = $exception->errorInfo;
            throw new \Exception($errorInfo[2], $errorInfo[1]);
        }
    }
}
