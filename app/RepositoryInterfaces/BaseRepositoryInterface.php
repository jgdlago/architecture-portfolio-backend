<?php

namespace App\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface BaseRepositoryInterface
{
    public function getAllModel(): LengthAwarePaginator;

    public function getModelById(int|Model $modelId): Model;

    public function createModel(array $modelDetails): Model;

    public function updateModel(array $modelDetails, Model|int $modelId): Model;

    public function deleteModel(Model|int $modelOrId): ?bool;
}
