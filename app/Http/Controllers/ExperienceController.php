<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExperienceRequest;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $experiences = QueryBuilder::for(
            auth()->user()->experiences()->getQuery()
        )
            ->allowedSorts(['start_date', 'end_date', 'created_at'])
            ->allowedFilters(['title', 'company', 'is_current'])
            ->defaultSort('-start_date')
            ->get();

        return ExperienceResource::collection($experiences);
    }

    public function show(Experience $experience): JsonResource
    {
        return new ExperienceResource($experience);
    }

    public function store(ExperienceRequest $request): JsonResource
    {
        $experience = auth()
            ->user()
            ->experiences()
            ->create($request->validated());

        return new ExperienceResource($experience, 201);
    }

    public function update(ExperienceRequest $request,Experience $experience): JsonResource
    {
        $experience->update($request->validated());

        return new ExperienceResource($experience);
    }

    public function destroy(Experience $experience): Response
    {
        $experience->delete();
        return response()->noContent(204);
    }
}
