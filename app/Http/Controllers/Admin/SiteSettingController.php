<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SiteSettingController extends Controller
{
    private const ALLOWED_KEYS = [
        'hero',
        'about',
        'process',
        'experience',
        'contact',
        'footer',
        'featured_projects',
        'footer_services',
        'seo',
        'navbar',
    ];

    public function index(): Response
    {
        return response(SiteSetting::query()->orderBy('key')->get());
    }

    public function upsert(Request $request): Response
    {
        $validated = $request->validate([
            'key' => ['required', 'string', 'max:255'],
            'value' => ['nullable', 'array'],
        ]);

        $key = $validated['key'];

        if (! in_array($key, self::ALLOWED_KEYS, true)) {
            throw ValidationException::withMessages([
                'key' => ['Chave de configuração não permitida.'],
            ]);
        }

        $valueRules = $this->valueRules($key);

        if ($valueRules !== []) {
            Validator::make($validated['value'] ?? [], $valueRules)->validate();
        }

        $setting = SiteSetting::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $validated['value'] ?? null],
        );

        return response($setting);
    }

    private function valueRules(string $key): array
    {
        return match ($key) {
            'hero' => [
                'title' => ['required', 'string', 'max:255'],
                'subtitle' => ['required', 'string', 'max:500'],
                'image_path' => ['nullable', 'string', 'max:255'],
            ],
            'about' => [
                'text' => ['required', 'string'],
                'image_path' => ['nullable', 'string', 'max:255'],
            ],
            'process' => [
                'title' => ['required', 'string', 'max:255'],
                'steps' => ['required', 'array', 'min:1'],
                'steps.*.title' => ['required', 'string', 'max:255'],
                'steps.*.description' => ['required', 'string'],
            ],
            'experience' => [
                'title' => ['required', 'string', 'max:255'],
                'subtitle' => ['required', 'string', 'max:500'],
                'blocks' => ['required', 'array', 'min:1'],
                'blocks.*.title' => ['required', 'string', 'max:255'],
                'blocks.*.items' => ['required', 'array', 'min:1'],
                'blocks.*.items.*' => ['required', 'string', 'max:255'],
            ],
            'contact' => [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'instagram_url' => ['nullable', 'string', 'max:255'],
                'linkedin_url' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'whatsapp_url' => ['nullable', 'string', 'max:255'],
            ],
            'footer' => [
                'brand_name' => ['nullable', 'string', 'max:255'],
                'brand_subtitle' => ['nullable', 'string', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:40'],
                'city' => ['nullable', 'string', 'max:255'],
                'instagram_url' => ['nullable', 'string', 'max:255'],
                'linkedin_url' => ['nullable', 'string', 'max:255'],
                'copyright' => ['nullable', 'string', 'max:255'],
                'cau' => ['nullable', 'string', 'max:50'],
            ],
            'featured_projects' => [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:500'],
            ],
            'footer_services' => [
                'title' => ['required', 'string', 'max:255'],
                'items' => ['required', 'array', 'min:1'],
                'items.*' => ['required', 'string', 'max:255'],
            ],
            'seo' => [
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string', 'max:320'],
            ],
            'navbar' => [
                'brand_name' => ['nullable', 'string', 'max:255'],
                'brand_role' => ['nullable', 'string', 'max:255'],
                'home_label' => ['nullable', 'string', 'max:50'],
                'projects_label' => ['nullable', 'string', 'max:50'],
                'about_label' => ['nullable', 'string', 'max:50'],
                'contact_label' => ['nullable', 'string', 'max:50'],
            ],
            default => [],
        };
    }
}
