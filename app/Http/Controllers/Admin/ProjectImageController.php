<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectImageResource;
use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use App\Support\PublicApiCache;

class ProjectImageController extends Controller
{
    public function reorder(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => [
                'integer',
                'distinct',
                'exists:project_images,id',
            ],
        ]);

        $belongsToProject = $project->images()
            ->whereIn('id', $validated['ids'])
            ->count();

        if ($belongsToProject !== count($validated['ids'])) {
            return response()->json([
                'message' => 'One or more images do not belong to this project.',
            ], 422);
        }

        foreach ($validated['ids'] as $index => $id) {
            $project->images()
                ->whereKey($id)
                ->update(['sort_order' => $index]);
        }

        PublicApiCache::bust();

        return response()->json([
            'message' => 'Project image order updated.',
        ]);
    }

    public function index(Project $project): AnonymousResourceCollection
    {
        return ProjectImageResource::collection($project->images);
    }

    public function store(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'image_path' => ['required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_cover' => ['sometimes', 'boolean'],
        ]);

        if (($validated['is_cover'] ?? false) === true) {
            $project->images()->update(['is_cover' => false]);
        }

        $image = $project->images()->create($validated);

        if ($image->is_cover) {
            $project->update(['cover_image_path' => $image->image_path]);
        }

        PublicApiCache::bust();

        return (new ProjectImageResource($image))->response()->setStatusCode(201);
    }

    public function update(Request $request, Project $project, ProjectImage $projectImage): ProjectImageResource
    {
        abort_unless($projectImage->project_id === $project->id, 404);

        $validated = $request->validate([
            'image_path' => ['sometimes', 'required', 'string', 'max:255'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_cover' => ['sometimes', 'boolean'],
        ]);

        if (($validated['is_cover'] ?? false) === true) {
            $project->images()
                ->where('id', '!=', $projectImage->id)
                ->update(['is_cover' => false]);
        }

        $projectImage->update($validated);

        if (($validated['is_cover'] ?? false) === true) {
            $project->update(['cover_image_path' => $projectImage->image_path]);
        }

        if (($validated['is_cover'] ?? null) === false
            && $project->cover_image_path === $projectImage->image_path
        ) {
            $project->update(['cover_image_path' => null]);
        }

        PublicApiCache::bust();

        return new ProjectImageResource($projectImage);
    }

    public function destroy(Project $project, ProjectImage $projectImage): Response
    {
        abort_unless($projectImage->project_id === $project->id, 404);

        $removedPath = $projectImage->image_path;

        $projectImage->delete();

        if ($project->cover_image_path === $removedPath) {
            $replacementCover = $project->images()->orderBy('sort_order')->orderBy('id')->first();

            $project->update([
                'cover_image_path' => $replacementCover?->image_path,
            ]);

            if ($replacementCover) {
                $project->images()->update(['is_cover' => false]);
                $replacementCover->update(['is_cover' => true]);
            }
        }

        PublicApiCache::bust();

        return response()->noContent();
    }
}
