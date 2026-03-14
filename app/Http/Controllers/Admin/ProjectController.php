<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectDetailResource;
use App\Http\Resources\ProjectListResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $projects = Project::query()
            ->with('category')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->paginate(20);

        return ProjectListResource::collection($projects);
    }

    public function show(Project $project): ProjectDetailResource
    {
        $project->load(['category', 'images']);

        return new ProjectDetailResource($project);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:projects,slug'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'cover_image_path' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'additional_info' => ['nullable', 'array'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $project = Project::create($validated);

        return (new ProjectDetailResource($project->load(['category', 'images'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Project $project): ProjectDetailResource
    {
        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:projects,slug,'.$project->id],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'project_category_id' => ['nullable', 'exists:project_categories,id'],
            'cover_image_path' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'area_m2' => ['nullable', 'numeric', 'min:0'],
            'additional_info' => ['nullable', 'array'],
            'is_featured' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'published_at' => ['nullable', 'date'],
        ]);

        $project->update($validated);

        return new ProjectDetailResource($project->load(['category', 'images']));
    }

    public function destroy(Project $project): Response
    {
        $project->delete();

        return response()->noContent();
    }
}
