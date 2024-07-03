<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceFormRequest;
use App\Http\Resources\ExperienceResource;
use App\RepositoryInterfaces\ExperienceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Exception;

class ExperienceController extends Controller
{
    protected ExperienceRepositoryInterface $experienceRepository;
    public function __construct(ExperienceRepositoryInterface $experienceRepository)
    {
        $this->experienceRepository = $experienceRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ExperienceResource::collection(
            $this->experienceRepository->getAllModel(true, request('perPage', 10))
        );
    }

    /**
     * @param  ExperienceFormRequest $experienceDetails
     * @return JsonResource
     * @throws Exception
     */
    public function store(ExperienceFormRequest $experienceDetails): JsonResource
    {
        return new ExperienceResource(
            $this->experienceRepository->createModel($experienceDetails->safe()->toArray())
        );
    }

    /**
     * @param  int $experienceId
     * @return JsonResource
     */
    public function show(int $experienceId): JsonResource
    {
        return new ExperienceResource(
            $this->experienceRepository->getModelById($experienceId)
        );
    }

    /**
     * @param  ExperienceFormRequest $experienceDetails
     * @param  int                $experienceId
     * @return JsonResource
     */
    public function update(ExperienceFormRequest $experienceDetails, int $experienceId): JsonResource
    {
        return new ExperienceResource(
            $this->experienceRepository->updateModel($experienceDetails->safe()->toArray(), $experienceId)
        );
    }

    /**
     * @param int $experienceId
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(int $experienceId): JsonResponse
    {
        return response()->json($this->experienceRepository->deleteModel($experienceId));
    }
}
