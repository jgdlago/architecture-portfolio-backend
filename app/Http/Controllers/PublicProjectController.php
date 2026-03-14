<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectDetailResource;
use App\Http\Resources\ProjectListResource;
use App\Models\Project;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::query()
            ->with('category')
            ->when(request('category'), function ($query, string $category): void {
                $query->whereHas('category', function ($categoryQuery) use ($category): void {
                    $categoryQuery->where('slug', $category);
                });
            })
            ->whereNotNull('published_at')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(12);

        return ProjectListResource::collection($projects);
    }

    public function show(string $slug): JsonResource
    {
        $project = Project::query()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->whereNotNull('published_at')
            ->firstOrFail();

        return new ProjectDetailResource($project);
    }
}
