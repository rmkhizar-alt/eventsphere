<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\VerificationToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'contact_number' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'enrolment_no' => 'nullable|string|max:50',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'contact_number' => $request->contact_number,
            'department' => $request->department,
            'enrolment_no' => $request->enrolment_no,
            'password' => $request->password,
            'role' => 'participant',
            'email_verified_at' => null,
        ]);

        $user->profile()->create([
            'department' => $request->department,
            'enrolment_no' => $request->enrolment_no,
        ]);

        $otp = (string) rand(100000, 999999);

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $otp,
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($request->email)->send(new \App\Mail\VerifyEmail($user, $otp));
            $emailSent = true;
        } catch (\Exception $e) {
            \Log::error('Verification email failed: ' . $e->getMessage());
            $emailSent = false;
        }

        $token = $user->createToken('eventosphere-token')->plainTextToken;

        return response()->json([
            'data' => ['user' => $user, 'token' => $token],
            'otp' => $otp,
            'email_sent' => $emailSent,
            'message' => $emailSent
                ? 'Account created. OTP sent to your email.'
                : 'Account created. Email could not be sent. Use the OTP shown on screen.',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = auth()->user();
        $token = $user->createToken('eventosphere-token')->plainTextToken;

        return response()->json([
            'data' => ['user' => $user, 'token' => $token],
            'message' => 'Logged in successfully'
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request)
    {
        return response()->json(['data' => $request->user()]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->email_verified_at) {
            $token = $user->createToken('eventosphere-token')->plainTextToken;
            return response()->json([
                'data' => ['user' => $user, 'token' => $token],
                'message' => 'Already verified.',
            ]);
        }

        $verificationToken = VerificationToken::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$verificationToken) {
            return response()->json(['message' => 'Invalid or expired OTP'], 422);
        }

        $verificationToken->update(['used' => true]);
        $user->forceFill(['email_verified_at' => now()])->save();
        $user->refresh();

        $token = $user->createToken('eventosphere-token')->plainTextToken;

        return response()->json([
            'data' => ['user' => $user, 'token' => $token],
            'message' => 'Email verified! Welcome to EventSphere.',
        ]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Account already verified. Please log in.']);
        }

        VerificationToken::where('user_id', $user->id)->update(['used' => true]);

        $otp = (string) rand(100000, 999999);

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $otp,
            'expires_at' => now()->addMinutes(15),
        ]);

        try {
            Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $otp));
        } catch (\Exception $e) {
            \Log::error('Resend OTP email failed: ' . $e->getMessage());
        }

        return response()->json([
            'otp' => $otp,
            'message' => 'New OTP sent to your email.',
        ]);
    }
}
