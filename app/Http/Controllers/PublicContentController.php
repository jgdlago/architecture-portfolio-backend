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
use App\Support\PublicApiCache;

class PublicContentController extends Controller
{
    public function home(): JsonResponse
    {
        $payload = PublicApiCache::remember('public-content:home', [], function (): array {
            $settings = SiteSetting::query()
                ->whereIn('key', ['hero', 'about', 'contact', 'footer', 'experience', 'process', 'featured_projects', 'footer_services', 'seo', 'navbar'])
                ->pluck('value', 'key');

            $featuredProjects = Project::query()
                ->with('category')
                ->where('is_featured', true)
                ->whereNotNull('published_at')
                ->orderBy('sort_order')
                ->limit(6)
                ->get();

            return [
                'settings' => $settings,
                'featured_projects' => [
                    'data' => ProjectListResource::collection($featuredProjects)->resolve(),
                ],
            ];
        });

        return response()->json($payload);
    }

    public function about(): JsonResponse
    {
        $payload = PublicApiCache::remember('public-content:about', [], function (): array {
            $adminEmail = config('app.admin_email');
            $user = User::where('email', $adminEmail)->first();

            $profile = null;
            $experiences = [];

            if ($user) {
                $user->load(['profile.city', 'experiences']);
                $profile = $user->profile ? (new ProfileResource($user->profile))->resolve() : null;
                $experiences = ExperienceResource::collection(
                    $user->experiences()->orderByDesc('start_date')->get()
                )->resolve();
            }

            $aboutSetting = SiteSetting::where('key', 'about')->first();

            return [
                'profile' => $profile,
                'experiences' => $experiences,
                'settings' => $aboutSetting?->value,
                'user' => $user ? [
                    'name' => $user->name,
                    'cau' => $user->cau,
                ] : null,
            ];
        });

        return response()->json($payload);
    }

    public function categories(): JsonResponse
    {
        $categories = PublicApiCache::remember('public-content:categories', [], function () {
            return ProjectCategory::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug'])
                ->toArray();
        });

        return response()->json($categories);
    }

    public function theme(): JsonResponse
    {
        $payload = PublicApiCache::remember('public-content:theme', [], function (): array {
            $settings = SiteSetting::query()
                ->whereIn('key', ['theme_colors', 'theme_typography'])
                ->pluck('value', 'key');

            return [
                'theme_colors' => $settings->get('theme_colors'),
                'theme_typography' => $settings->get('theme_typography'),
            ];
        });

        return response()->json($payload);
    }
}
