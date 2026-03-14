<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\PageVisit;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function stats(): JsonResponse
    {
        $publishedProjects = Project::whereNotNull('published_at')->count();
        $draftProjects = Project::whereNull('published_at')->count();

        $unreadMessages = ContactMessage::where('is_read', false)->count();
        $totalMessages = ContactMessage::count();

        $recentMessages = ContactMessage::query()
            ->where('is_read', false)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get(['id', 'name', 'email', 'subject', 'message', 'created_at']);

        $visitsLast7Days = PageVisit::query()
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->distinct('visitor_key')
            ->count('visitor_key');

        $visitsLast30Days = PageVisit::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->distinct('visitor_key')
            ->count('visitor_key');

        $pageViewsLast7Days = PageVisit::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $pageViewsLast30Days = PageVisit::where('created_at', '>=', Carbon::now()->subDays(30))->count();

        $topPages = PageVisit::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('page, COUNT(*) as views, COUNT(DISTINCT visitor_key) as unique_visitors')
            ->groupBy('page')
            ->orderByDesc('views')
            ->limit(10)
            ->get();

        $dailyVisits = PageVisit::query()
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as views, COUNT(DISTINCT visitor_key) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'projects' => [
                'published' => $publishedProjects,
                'drafts' => $draftProjects,
                'total' => $publishedProjects + $draftProjects,
            ],
            'messages' => [
                'unread' => $unreadMessages,
                'total' => $totalMessages,
                'recent' => $recentMessages,
            ],
            'visits' => [
                'last_7_days' => $visitsLast7Days,
                'last_30_days' => $visitsLast30Days,
                'page_views_last_7_days' => $pageViewsLast7Days,
                'page_views_last_30_days' => $pageViewsLast30Days,
                'top_pages' => $topPages,
                'daily' => $dailyVisits,
            ],
        ]);
    }
}
