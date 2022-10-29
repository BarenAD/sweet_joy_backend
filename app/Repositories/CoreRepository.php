<?php

namespace App\Repositories;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CoreRepository
 *
 * @property Builder $model
 *
 * @package App\Repositories
 */
abstract class CoreRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->model = app($this->getModelClass());
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function find(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function store(array $params): Model
    {
        return $this->model->create($params);
    }

    public function update(int $id, array $params = []): Model
    {
        $modelItem = $this->model->where('id', $id)->findOrFail();

        return tap($modelItem)->update($params);
    }

    public function insert(array $params): bool
    {
        $dateNow = Carbon::now();
        foreach ($params as &$param) {
            if (!isset($param['created_at'])) {
                $param['created_at'] = $dateNow;
            }
            if (!isset($param['updated_at'])) {
                $param['updated_at'] = $dateNow;
            }
        }
        return $this->model->insert($params);
    }

    public function destroy(int $id): int
    {
        return $this->model->destroy($id);
    }

    abstract protected function getModelClass();
}
