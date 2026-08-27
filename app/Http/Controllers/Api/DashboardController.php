<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Bookmark;
use App\Models\Certificate;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function student(Request $request)
    {
        $user = $request->user();

        $stats = [
            'total_registrations' => Registration::where('user_id', $user->id)->count(),
            'confirmed_registrations' => Registration::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'upcoming_events' => Registration::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereHas('event', function ($q) {
                    $q->where('event_date', '>=', now()->toDateString());
                })
                ->count(),
            'completed_events' => Registration::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->whereHas('event', function ($q) {
                    $q->where('event_date', '<', now()->toDateString());
                })
                ->count(),
            'certificates_earned' => Certificate::where('user_id', $user->id)->count(),
            'feedback_given' => \App\Models\Feedback::where('user_id', $user->id)->count(),
            'bookmarks' => Bookmark::where('user_id', $user->id)->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
        ];

        $upcomingEvents = Registration::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->whereHas('event', function ($q) {
                $q->where('event_date', '>=', now()->toDateString());
            })
            ->with(['event' => function ($q) {
                $q->select(['id', 'title', 'slug', 'event_date', 'start_time', 'venue', 'category']);
            }])
            ->latest()
            ->take(5)
            ->get(['id', 'event_id', 'status', 'registered_at']);

        $recentBookmarks = Bookmark::where('user_id', $user->id)
            ->with(['event' => function ($q) {
                $q->select(['id', 'title', 'slug', 'event_date', 'category']);
            }])
            ->latest()
            ->take(5)
            ->get(['id', 'event_id', 'created_at']);

        return response()->json([
            'data' => [
                'stats' => $stats,
                'upcoming_events' => $upcomingEvents,
                'recent_bookmarks' => $recentBookmarks,
            ],
        ]);
    }
}
