<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormSubmitted;

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

        // Send email to the college contact
        try {
            Mail::to('rmkhizar@gmail.com')->send(new ContactFormSubmitted($request->all()));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Contact form email failed: ' . $e->getMessage());
        }

        // Also store in database if Contact model exists
        // Contact::create([...]);

        return response()->json(['message' => 'Message received successfully']);
    }
}