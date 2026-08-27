<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'venue_rating' => 'required|integer|between:1,5',
            'coordination_rating' => 'required|integer|between:1,5',
            'technical_rating' => 'required|integer|between:1,5',
            'hospitality_rating' => 'required|integer|between:1,5',
            'comments' => 'nullable|string',
        ]);

        // Check if user has attended
        $hasAttended = $request->user()->registrations()
            ->where('event_id', $event->id)
            ->whereHas('attendance', function ($q) {
                $q->where('attended', true);
            })
            ->exists();

        if (!$hasAttended) {
            return response()->json(['message' => 'Feedback only allowed after attendance'], 422);
        }

        // Check if already submitted
        $existing = Feedback::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->update($request->only([
                'rating',
                'venue_rating',
                'coordination_rating',
                'technical_rating',
                'hospitality_rating',
                'comments',
            ]));
            return response()->json(['data' => $existing, 'message' => 'Feedback updated']);
        }

        $feedback = Feedback::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            ...$request->only([
                'rating',
                'venue_rating',
                'coordination_rating',
                'technical_rating',
                'hospitality_rating',
                'comments',
            ]),
        ]);

        return response()->json(['data' => $feedback, 'message' => 'Feedback submitted']);
    }

    public function index(Event $event)
    {
        $feedbacks = Feedback::where('event_id', $event->id)
            ->latest()
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'username', 'profile_photo_path');
            }])
            ->paginate(20);

        return response()->json(['data' => $feedbacks]);
    }

    public function average(Event $event)
    {
        $average = $event->feedbacks()->avg('rating');

        return response()->json(['data' => [
            'average_rating' => round($average ?? 0, 1),
            'total_feedbacks' => $event->feedback()->count(),
        ]]);
    }

    public function byRating(Event $event, $minRating = 3)
    {
        $feedbacks = Feedback::where('event_id', $event->id)
            ->where('rating', '>=', $minRating)
            ->latest()
            ->with(['user' => function ($q) {
                $q->select('id', 'name', 'username');
            }])
            ->paginate(20);

        return response()->json(['data' => $feedbacks]);
    }

    public function myFeedback(Request $request, Event $event)
    {
        $feedback = Feedback::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json(['data' => $feedback]);
    }
}