<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectCategoryController extends Controller
{
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

        return response($projectCategory);
    }

    public function destroy(ProjectCategory $projectCategory): Response
    {
        $projectCategory->delete();

        return response()->noContent();
    }
}
