<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PageVisitController extends Controller
{
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'page' => ['required', 'string', 'max:100'],
            'path' => ['required', 'string', 'max:500'],
        ]);

        PageVisit::create([
            'page' => $validated['page'],
            'path' => $validated['path'],
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 512),
            'referer' => mb_substr((string) $request->header('referer'), 0, 500),
        ]);

        return response(['message' => 'ok'], 201);
    }
}
