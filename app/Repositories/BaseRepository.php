<?php

namespace App\Repositories;

use App\RepositoryInterfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     *  return all models
     *
     * @return LengthAwarePaginator
     */
    public function getAllModel(): LengthAwarePaginator
    {
        return $this->model::paginate(10);
    }

    /**
     *  return model by id
     *
     * @param  Model|int $modelId
     * @return Model
     */
    public function getModelById(Model|int $modelId): Model
    {
        return is_int($modelId) ? $this->model::findOrFail($modelId) : $modelId;
    }

    /**
     *  create a new model
     *
     * @param  array $modelDetails
     * @return Model
     * @throws Exception
     */
    public function createModel(array $modelDetails): Model
    {
        return $this->model::create($modelDetails);
    }

    /**
     * update model
     *
     * @param  array     $modelDetails
     * @param  Model|int $modelId
     * @return Model
     */
    public function updateModel(array $modelDetails, Model|int $modelId): Model
    {
        $model = is_int($modelId) ? $this->model::findOrFail($modelId) : $modelId;
        $model->update($modelDetails);
        return $model;
    }

    /**
     * Delete a model by ID or instance.
     *
     * @param  Model|int $modelOrId
     * @return bool|null
     * @throws Exception
     */
    public function deleteModel(Model|int $modelOrId): ?bool
    {
        if ($modelOrId instanceof Model) {
            $modelOrId->delete();
        }

        $deleted = $this->model::destroy($modelOrId);

        return $deleted ? true : null;
    }
}
