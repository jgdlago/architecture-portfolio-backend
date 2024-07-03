<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectFormRequest;
use App\Http\Resources\ProjectResource;
use App\RepositoryInterfaces\ProjectRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Exception;

class ProjectController extends Controller
{
    protected ProjectRepositoryInterface $projectRepository;
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ProjectResource::collection(
            $this->projectRepository->getAllModel(true, request('perPage', 10))
        );
    }

    /**
     * @param  ProjectFormRequest $projectDetails
     * @return JsonResource
     * @throws Exception
     */
    public function store(ProjectFormRequest $projectDetails): JsonResource
    {
        return new ProjectResource(
            $this->projectRepository->createModel($projectDetails->safe()->toArray())
        );
    }

    /**
     * @param  int $projectId
     * @return JsonResource
     */
    public function show(int $projectId): JsonResource
    {
        return new ProjectResource(
            $this->projectRepository->getModelById($projectId)
        );
    }

    /**
     * @param  ProjectFormRequest $projectDetails
     * @param  int                $projectId
     * @return JsonResource
     */
    public function update(ProjectFormRequest $projectDetails, int $projectId): JsonResource
    {
        return new ProjectResource(
            $this->projectRepository->updateModel($projectDetails->safe()->toArray(), $projectId)
        );
    }

    /**
     * @param int $projectId
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $projectId): JsonResponse
    {
        return response()->json($this->projectRepository->deleteModel($projectId));
    }
}
