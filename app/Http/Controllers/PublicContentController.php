<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExperienceResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ProjectListResource;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class PublicContentController extends Controller
{
    public function home(): JsonResponse
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

        return response()->json([
            'settings' => $settings,
            'featured_projects' => [
                'data' => ProjectListResource::collection($featuredProjects),
            ],
        ]);
    }

    public function about(): JsonResponse
    {
        $adminEmail = config('app.admin_email');
        $user = User::where('email', $adminEmail)->first();

        $profile = null;
        $experiences = [];

        if ($user) {
            $user->load(['profile.city', 'experiences']);
            $profile = $user->profile ? new ProfileResource($user->profile) : null;
            $experiences = ExperienceResource::collection(
                $user->experiences()->orderByDesc('start_date')->get()
            );
        }

        $aboutSetting = SiteSetting::where('key', 'about')->first();

        return response()->json([
            'profile' => $profile,
            'experiences' => $experiences,
            'settings' => $aboutSetting?->value,
            'user' => $user ? [
                'name' => $user->name,
                'cau' => $user->cau,
            ] : null,
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = ProjectCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug']);

        return response()->json($categories);
    }
}
