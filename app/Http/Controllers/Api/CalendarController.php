<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\Bookmark;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function exportIcs(Request $request, Event $event)
    {
        $user = $request->user();
        
        // Check if user is registered or bookmark
        $isRegistered = Registration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'waitlisted'])
            ->exists();
        
        $isBookmarked = Bookmark::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();
        
        if (!$isRegistered && !$isBookmarked) {
            return response()->json(['message' => 'You must be registered or bookmarked to export'], 403);
        }

        $dtStart = date('Ymd\THis', strtotime($event->event_date . ' ' . $event->start_time));
        $dtEnd = date('Ymd\THis', strtotime($event->event_date . ' ' . $event->end_time));
        $dtStamp = date('Ymd\THis');

        $icsContent = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//EventSphere//EN
BEGIN:VEVENT
UID:" . $event->id . "@eventsphere
DTSTAMP:$dtStamp
DTSTART:$dtStart
DTEND:$dtEnd
SUMMARY:" . $event->title . "
LOCATION:" . $event->venue . "
DESCRIPTION:" . strip_tags($event->description) . "
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR";

        return response($icsContent, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->slug . '.ics"',
        ]);
    }
}
