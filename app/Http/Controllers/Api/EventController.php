<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Attendance;
use App\Models\Feedback;
use App\Models\Certificate;
use App\Models\MediaGallery;
use App\Models\Bookmark;
use App\Models\SavedMedia;
use App\Models\Notification;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query()->approved();

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%')
                    ->orWhere('venue', 'like', '%' . $request->q . '%');
            });
        }

        // Date range
        if ($request->has('date_from')) {
            $query->where('event_date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('event_date', '<=', $request->date_to);
        }

        // Featured events first
        $query->orderBy('highlight', 'desc');

        // Paginate
        $events = $query->withCount(['registrations' => function ($q) {
            $q->where('status', 'confirmed');
        }])->paginate(12);

        return response()->json(['data' => $events]);
    }

    public function filter(Request $request)
    {
        $query = Event::query()->approved();

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('featured')) {
            $query->where('highlight', true);
        }

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->withCount(['registrations' => function ($q) {
            $q->where('status', 'confirmed');
        }])->paginate(12);

        return response()->json(['data' => $events]);
    }

    public function show(Event $event)
    {
        $event->load([
            'organizer',
            'registrations',
            'registrations.attendance',
            'feedback',
            'mediaGallery',
            'certificates',
        ]);

        $event->avg_rating = $event->feedback()->avg('rating') ?? 0;
        $event->feedback_count = $event->feedback()->count();

        return response()->json(['data' => $event]);
    }

    public function register(Request $request, Event $event)
    {
        $request->validate([
            'status' => 'sometimes|required|in:confirmed,waitlisted,cancelled',
        ]);

        // Check if user already registered
        $existing = Registration::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'confirmed']);
                return response()->json(['data' => $existing, 'message' => 'Registration reactivated']);
            }
            return response()->json(['message' => 'Already registered for this event'], 422);
        }

        // Check seat availability
        $seatsAvailable = $event->seats_available;
        $waitlist = false;

        if ($seatsAvailable <= 0 && $event->waitlist_enabled) {
            $waitlist = true;
        } elseif ($seatsAvailable <= 0) {
            return response()->json(['message' => 'Event is full'], 422);
        }

        $registration = Registration::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            'status' => $waitlist ? 'waitlisted' : 'confirmed',
            'qr_token' => Str::random(40),
        ]);

        // If waitlisted, try to auto-promote
        if ($waitlist) {
            $this->tryPromoteWaitlist($event);
        }

        return response()->json(['data' => $registration, 'message' => $waitlist ? 'Registered on waitlist' : 'Registered successfully']);
    }

    private function tryPromoteWaitlist(Event $event)
    {
        $waitlisted = Registration::where('event_id', $event->id)
            ->where('status', 'waitlisted')
            ->orderBy('registered_at')
            ->first();

        if ($waitlisted) {
            $waitlisted->update(['status' => 'confirmed']);

            // Create notification for the promoted user
            Notification::create([
                'user_id' => $waitlisted->user_id,
                'type' => 'event',
                'icon' => 'star',
                'title' => 'Waitlist Promoted',
                'body' => 'You have been promoted from the waitlist for ' . $event->title,
                'href' => '/events/' . $event->slug,
            ]);
        }
    }

    public function myRegistrations(Request $request)
    {
        $registrations = Registration::where('user_id', $request->user()->id)
            ->with(['event' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->get(['id', 'event_id', 'status', 'qr_token', 'registered_at']);

        return response()->json(['data' => $registrations]);
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $registration = Registration::where('qr_token', $request->qr_token)->firstOrFail();

        if ($registration->attendance->first()) {
            return response()->json(['message' => 'Already checked in'], 422);
        }

        Attendance::create([
            'registration_id' => $registration->id,
            'attended' => true,
            'marked_by' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Check-in successful']);
    }

    public function feedback(Request $request, Event $event)
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
            $existing->update($request->only(['rating', 'venue_rating', 'coordination_rating', 'technical_rating', 'hospitality_rating', 'comments']));
            return response()->json(['data' => $existing, 'message' => 'Feedback updated']);
        }

        $feedback = Feedback::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
            ...$request->only(['rating', 'venue_rating', 'coordination_rating', 'technical_rating', 'hospitality_rating', 'comments']),
        ]);

        return response()->json(['data' => $feedback, 'message' => 'Feedback submitted']);
    }

    public function bookmark(Request $request, Event $event)
    {
        $existing = Bookmark::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Unbookmarked']);
        }

        Bookmark::create([
            'event_id' => $event->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Bookmarked']);
    }

    public function savedMedia(Request $request, MediaGallery $media)
    {
        $existing = SavedMedia::where('media_id', $media->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['message' => 'Removed from saved']);
        }

        SavedMedia::create([
            'media_id' => $media->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(['message' => 'Saved']);
    }
}