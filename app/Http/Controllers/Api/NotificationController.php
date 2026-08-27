<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        $unreadCount = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json([
            'data' => ['notifications' => $notifications, 'unread_count' => $unreadCount],
        ]);
    }

    public function markRead(Request $request, Notification $notification)
    {
        // Ensure the notification belongs to the user
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['data' => $notification, 'message' => 'Marked as read']);
    }

    public function markUnread(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $notification->update(['is_read' => false]);

        return response()->json(['data' => $notification, 'message' => 'Marked as unread']);
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All marked as read']);
    }

    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'sometimes|string|max:50',
            'href' => 'nullable|string',
        ]);

        $userId = $request->user()->id;

        if ($request->filled('user_id')) {
            $userId = $request->user_id;
        }

        $notification = Notification::create([
            'user_id' => $userId,
            'title' => $request->title,
            'body' => $request->body,
            'type' => $request->type ?? 'general',
            'href' => $request->href,
        ]);

        return response()->json(['data' => $notification, 'message' => 'Notification sent']);
    }

    public function getUnreadCount(Request $request)
    {
        $count = Notification::where('user_id', $request->user()->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['data' => ['unread_count' => $count]]);
    }

    public function getTypes(Request $request)
    {
        $types = Notification::where('user_id', $request->user()->id)
            ->whereNotNull('type')
            ->select('type')
            ->distinct()
            ->pluck('type');

        return response()->json(['data' => ['types' => $types]]);
    }
}