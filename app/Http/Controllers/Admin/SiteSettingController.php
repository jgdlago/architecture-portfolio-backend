<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SiteSettingController extends Controller
{
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

        $setting = SiteSetting::query()->updateOrCreate(
            ['key' => $validated['key']],
            ['value' => $validated['value'] ?? null],
        );

        return response($setting);
    }
}
