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

class ProjectImageController extends Controller
{
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

        $image = $project->images()->create($validated);

        if ($image->is_cover) {
            $project->update(['cover_image_path' => $image->image_path]);
        }

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

        $projectImage->update($validated);

        if (($validated['is_cover'] ?? false) === true) {
            $project->update(['cover_image_path' => $projectImage->image_path]);
        }

        return new ProjectImageResource($projectImage);
    }

    public function destroy(Project $project, ProjectImage $projectImage): Response
    {
        abort_unless($projectImage->project_id === $project->id, 404);

        $projectImage->delete();

        return response()->noContent();
    }
}
