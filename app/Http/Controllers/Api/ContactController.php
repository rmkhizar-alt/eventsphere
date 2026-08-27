<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        // Store contact message in database (if Contact model exists)
        // For now, we'll send email and return success
        try {
            // Send email to the college contact
            Mail::to('rmkhizar@gmail.com')->send(new \App\Mail\ContactFormSubmitted($request->all()));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Contact form email failed: ' . $e->getMessage());
        }

        return response()->json(['message' => 'Message received successfully']);
    }

    public function index(Request $request)
    {
        // Admin can view all contact messages
        if ($request->user()->role === 'admin') {
            $messages = \App\Models\Contact::latest()->paginate(20);
            return response()->json(['data' => $messages]);
        }

        // Regular users can only see their own if they submitted
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    public function show($id)
    {
        $message = \App\Models\Contact::findOrFail($id);
        return response()->json(['data' => $message]);
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'response' => 'required|string',
        ]);

        $message = \App\Models\Contact::findOrFail($id);
        $message->update(['response' => $request->response, 'replied_at' => now(), 'replied_by' => $request->user()->id]);

        return response()->json(['data' => $message, 'message' => 'Reply sent']);
    }

    public function statistics(Request $request)
    {
        $total = \App\Models\Contact::count();
        $replied = \App\Models\Contact::whereNotNull('replied_at')->count();
        $unreplied = $total - $replied;

        return response()->json(['data' => [
            'total_messages' => $total,
            'replied' => $replied,
            'unreplied' => $unreplied,
            'response_rate' => $total > 0 ? round(($replied / $total) * 100) : 0,
        ]]);
    }
}