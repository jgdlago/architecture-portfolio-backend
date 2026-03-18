<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use App\Support\PublicApiCache;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectCategoryController extends Controller
{
    public function reorder(Request $request): Response
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:project_categories,id'],
        ]);

        foreach ($validated['ids'] as $index => $id) {
            ProjectCategory::query()
                ->whereKey($id)
                ->update(['sort_order' => $index]);
        }

        PublicApiCache::bust();

        return response([
            'message' => 'Project categories order updated.',
        ]);
    }

    public function index(): Response
    {
        return response(ProjectCategory::query()->orderBy('sort_order')->get());
    }

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:project_categories,slug'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $category = ProjectCategory::create($validated);

        PublicApiCache::bust();

        return response($category, 201);
    }

    public function update(Request $request, ProjectCategory $projectCategory): Response
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', 'unique:project_categories,slug,'.$projectCategory->id],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $projectCategory->update($validated);

        PublicApiCache::bust();

        return response($projectCategory);
    }

    public function destroy(ProjectCategory $projectCategory): Response
    {
        $projectCategory->delete();

        PublicApiCache::bust();

        return response()->noContent();
    }
}
