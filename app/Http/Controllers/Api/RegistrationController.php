<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $event = Event::findOrFail($request->event_id);

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

    public function cancel(Request $request, Registration $registration)
    {
        // Only owner can cancel
        if ($registration->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($registration->status === 'cancelled') {
            return response()->json(['message' => 'Already cancelled'], 422);
        }

        $seatsBefore = $registration->event->seats_booked;
        $registration->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        // Auto-promote from waitlist if applicable
        if ($registration->status === 'cancelled') {
            $this->tryPromoteWaitlist($registration->event);
        }

        return response()->json(['data' => $registration, 'message' => 'Registration cancelled']);
    }

    public function markAttended(Request $request, Registration $registration)
    {
        // Only organizer can mark attended
        if ($request->user()->role !== 'organizer' && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $registration->attendance()->update(['attended' => true]);

        // Check if certificate should be issued
        $event = $registration->event;
        $certificate = Certificate::where('event_id', $event->id)
            ->where('user_id', $request->user()->id)
            ->first();

        // If certificate_fee is set and not paid, mark fee as pending for acknowledgment
        if ($event->certificate_fee && $event->certificate_fee > 0) {
            if ($certificate) {
                $certificate->update(['fee_paid' => true]);
            } else {
                Certificate::create([
                    'event_id' => $event->id,
                    'user_id' => $request->user()->id,
                    'fee_paid' => true,
                    'certificate_path' => 'certificates/' . $event->slug . '/' . $request->user()->id . '.pdf',
                    'issued_at' => now()->toDateTimeString(),
                ]);
            }
        } elseif (!$certificate) {
            // Issue certificate automatically if no fee
            Certificate::create([
                'event_id' => $event->id,
                'user_id' => $request->user()->id,
                'fee_paid' => false,
                'certificate_path' => 'certificates/' . $event->slug . '/' . $request->user()->id . '.pdf',
                'issued_at' => now()->toDateTimeString(),
            ]);
        }

        // Create notification
        Notification::create([
            'user_id' => $request->user()->id,
            'type' => 'event',
            'icon' => 'check-circle',
            'title' => 'Attendance Marked',
            'body' => 'Your attendance has been marked for ' . $event->title,
            'href' => '/events/' . $event->slug,
        ]);

        return response()->json(['data' => $registration, 'message' => 'Attendance marked']);
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

        // Check if user is registered for this organizer's event
        if ($registration->event->organizer_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized - not your event'], 403);
        }

        Attendance::create([
            'registration_id' => $registration->id,
            'attended' => true,
            'marked_by' => $request->user()->id,
        ]);

        // Create notification for the registrant
        Notification::create([
            'user_id' => $registration->user_id,
            'type' => 'event',
            'icon' => 'check-circle',
            'title' => 'Check-in Successful',
            'body' => 'Your attendance has been marked for ' . $registration->event->title,
            'href' => '/events/' . $registration->event->slug,
        ]);

        return response()->json(['data' => $registration, 'message' => 'Check-in successful']);
    }
}