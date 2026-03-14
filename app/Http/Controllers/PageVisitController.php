<?php

namespace App\Http\Controllers;

use App\Models\PageVisit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class PageVisitController extends Controller
{
    private const DEDUPE_WINDOW_MINUTES = 30;

    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'page' => ['required', 'string', 'max:100'],
            'path' => ['required', 'string', 'max:500'],
            'visitor_id' => ['nullable', 'string', 'max:120'],
        ]);

        $visitorKey = $this->resolveVisitorKey($request, $validated['visitor_id'] ?? null);

        $isDuplicate = PageVisit::query()
            ->where('visitor_key', $visitorKey)
            ->where('path', $validated['path'])
            ->where('created_at', '>=', Carbon::now()->subMinutes(self::DEDUPE_WINDOW_MINUTES))
            ->exists();

        if ($isDuplicate) {
            return response(['message' => 'ignored_duplicate'], 202);
        }

        PageVisit::create([
            'page' => $validated['page'],
            'path' => $validated['path'],
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 512),
            'referer' => mb_substr((string) $request->header('referer'), 0, 500),
            'visitor_key' => $visitorKey,
        ]);

        return response(['message' => 'ok'], 201);
    }

    private function resolveVisitorKey(Request $request, ?string $visitorId): string
    {
        $normalizedVisitorId = trim((string) ($visitorId ?: $request->header('X-Visitor-Id', '')));
        $normalizedVisitorId = mb_substr($normalizedVisitorId, 0, 120);

        $fingerprint = implode('|', [
            $normalizedVisitorId,
            (string) $request->ip(),
            mb_substr((string) $request->userAgent(), 0, 255),
            mb_substr((string) $request->header('accept-language'), 0, 120),
        ]);

        return hash('sha256', $fingerprint);
    }
}
