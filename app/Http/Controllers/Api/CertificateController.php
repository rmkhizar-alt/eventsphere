<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as DomPDF;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Certificate::where('user_id', $user->id);

        // Filter by event if specified
        if ($request->has('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $certificates = $query->with(['event' => function ($q) {
            $q->select(['id', 'title', 'slug', 'category']);
        }])->latest()->paginate(20);

        return response()->json(['data' => $certificates]);
    }

    public function show($userId, Event $event)
    {
        $certificate = Certificate::where('event_id', $event->id)
            ->where('user_id', $userId)
            ->firstOrFail();

        return response()->json(['data' => $certificate]);
    }

    public function download(Certificate $certificate)
    {
        // In a real implementation, serve the PDF file
        // For now, return the path info with download token
        return response()->json(['data' => $certificate, 'message' => 'Certificate download path']);
    }

    public function issue(Request $request, Event $event)
    {
        $request->validate([
            'user_ids' => 'sometimes|required|array',
            'fee' => 'sometimes|boolean',
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
                        'fee_paid' => $request->filled('fee') ? true : false,
                        'certificate_path' => 'certificates/' . $event->slug . '/' . $userId . '.pdf',
                        'issued_at' => now()->toDateTimeString(),
                    ]);
                }
            }
        }

        $message = $request->filled('fee')
            ? 'Certificates issued with fee payment recorded'
            : 'Certificates issued to eligible participants';

        return response()->json(['message' => $message]);
    }

    public function verify(Request $request, $certificateId)
    {
        $certificate = Certificate::where('id', $certificateId)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        return response()->json(['data' => $certificate, 'message' => 'Certificate verified']);
    }

    public function generatePdf(Request $request, Certificate $certificate)
    {
        // In production, use DomPDF to generate actual PDF
        // For now, return success response
        return response()->json(['data' => $certificate, 'message' => 'PDF generation initiated']);
    }
}