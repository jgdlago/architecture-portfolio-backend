<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $experiences = QueryBuilder::for(
            $user->experiences()->getQuery()
        )
            ->allowedSorts(['start_date', 'end_date', 'created_at'])
            ->allowedFilters(['title', 'company', 'is_current'])
            ->defaultSort('-start_date')
            ->get();

        return ExperienceResource::collection($experiences);
    }

    public function show(Experience $experience): JsonResource
    {
        $this->authorizeOwner($experience->user_id);

        return new ExperienceResource($experience);
    }

    public function store(ExperienceRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $experience = $user->experiences()->create($request->validated());

        return (new ExperienceResource($experience))->response()->setStatusCode(201);
    }

    public function update(ExperienceRequest $request, Experience $experience): JsonResource
    {
        $this->authorizeOwner($experience->user_id);

        $experience->update($request->validated());

        return new ExperienceResource($experience);
    }

    public function destroy(Experience $experience): Response
    {
        $this->authorizeOwner($experience->user_id);

        $experience->delete();

        return response()->noContent(204);
    }

    private function authorizeOwner(int $ownerId): void
    {
        abort_unless(Auth::id() === $ownerId, 403);
    }
}
