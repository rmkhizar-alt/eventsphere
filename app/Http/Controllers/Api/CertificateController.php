<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as DomPDF;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $certificates = Certificate::where('user_id', $request->user()->id)
            ->with(['event' => function ($q) {
                $q->select(['id', 'title', 'slug']);
            }])
            ->get();

        return response()->json(['data' => $certificates]);
    }

    public function payFee(Request $request, $eventId)
    {
        $request->validate([
            'event_id' => ['required', 'exists:events,id'],
        ]);

        $certificate = Certificate::where('event_id', $eventId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $certificate->update(['fee_paid' => true]);

        return response()->json(['data' => $certificate, 'message' => 'Fee marked as paid']);
    }

    public function show(Certificate $certificate)
    {
        return response()->json(['data' => $certificate]);
    }

    public function download(Certificate $certificate)
    {
        // In a real implementation, serve the PDF file
        // For now, return the path info
        return response()->json(['data' => $certificate, 'message' => 'Certificate download path']);
    }

    public function issue(Request $request, Event $event)
    {
        $request->validate([
            'user_ids' => 'sometimes|array',
        ]);

        $eligibleUsers = Registration::where('event_id', $event->id)
            ->whereHas('attendance', function ($q) {
                $q->where('attended', true);
            })
            ->get();

        $userIds = $request->user_ids ?? $eligibleUsers->pluck('user_id')->toArray();

        foreach ($userIds as $userId) {
            $reg = Registration::where('event_id', $event->id)
                ->where('user_id', $userId)
                ->first();

            if ($reg && $reg->attendance->first()?->attended) {
                $existing = Certificate::where('event_id', $event->id)
                    ->where('user_id', $userId)
                    ->first();

                if (!$existing) {
                    Certificate::create([
                        'event_id' => $event->id,
                        'user_id' => $userId,
                        'fee_paid' => false, // No real payment
                        'certificate_path' => 'certificates/' . $event->slug . '/' . $userId . '.pdf',
                        'issued_at' => now()->toDateTimeString(),
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Certificates issued to eligible participants']);
    }
}