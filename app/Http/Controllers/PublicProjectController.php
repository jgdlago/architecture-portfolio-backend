<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectDetailResource;
use App\Http\Resources\ProjectListResource;
use App\Models\Project;
use App\Support\PublicApiCache;
use Illuminate\Http\JsonResponse;

class PublicProjectController extends Controller
{
    public function index(): JsonResponse
    {
        $categoryFilter = trim((string) request('category', request('category_slug', '')));
        $normalizedFilter = mb_strtolower($categoryFilter);
        $page = max(1, (int) request('page', 1));

        $payload = PublicApiCache::remember('public-projects:index', [
            'category' => $normalizedFilter,
            'page' => $page,
        ], function () use ($categoryFilter, $normalizedFilter): array {
            $projects = Project::query()
                ->with('category')
                ->when($categoryFilter !== '', function ($query) use ($categoryFilter, $normalizedFilter): void {
                    $query->whereHas('category', function ($categoryQuery) use ($categoryFilter, $normalizedFilter): void {
                        $categoryQuery->whereRaw('LOWER(TRIM(slug)) = ?', [$normalizedFilter])
                            ->orWhereRaw('LOWER(TRIM(name)) = ?', [$normalizedFilter]);

                        if (ctype_digit($categoryFilter)) {
                            $categoryQuery->orWhere('id', (int) $categoryFilter);
                        }
                    });
                })
                ->whereNotNull('published_at')
                ->orderByDesc('is_featured')
                ->orderBy('sort_order')
                ->orderByDesc('published_at')
                ->paginate(12);

            return ProjectListResource::collection($projects)
                ->response()
                ->getData(true);
        });

        return response()->json($payload);
    }

    public function show(string $slug): JsonResponse
    {
        $payload = PublicApiCache::remember('public-projects:show', [
            'slug' => $slug,
        ], function () use ($slug): array {
            $project = Project::query()
                ->with(['category', 'images'])
                ->where('slug', $slug)
                ->whereNotNull('published_at')
                ->firstOrFail();

            return (new ProjectDetailResource($project))->resolve();
        });

        return response()->json([
            'data' => $payload,
        ]);
    }
}
