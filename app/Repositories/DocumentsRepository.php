<?php


namespace App\Repositories;


use App\Models\Documents;

/**
 * Class DocumentsRepository
 * @package App\Repositories
 */
class DocumentsRepository
{
    private $model;

    /**
     * LocationsDocumentsRepository constructor.
     * @param Documents $documents
     */
    public function __construct(Documents $documents)
    {
        $this->model = $documents;
    }

    /**
     * @param int|null $id
     * @return Documents[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDocuments(int $id = null)
    {
        if ($id) {
            return $this->model::findOrFail($id);
        }
        return $this->model::all();
    }

    /**
     * @param string $name
     * @param string $uri
     * @return mixed
     */
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
