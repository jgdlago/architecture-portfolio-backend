<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectListResource;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Http\Response;

class PublicContentController extends Controller
{
    public function home(): Response
    {
        $settings = SiteSetting::query()
            ->whereIn('key', ['hero', 'about', 'contact', 'footer', 'experience', 'process'])
            ->pluck('value', 'key');

        $featuredProjects = Project::query()
            ->with('category')
            ->where('is_featured', true)
            ->whereNotNull('published_at')
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return response([
            'settings' => $settings,
            'featured_projects' => ProjectListResource::collection($featuredProjects),
        ]);
    }
}
