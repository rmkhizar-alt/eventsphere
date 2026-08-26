<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    public function showForm(): View
    {
        return view('auth.verify-otp', [
            'email' => session('email'),
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors(['otp' => 'User nahi mila.']);
        }

        if ($user->email_verified_at) {
            Auth::login($user);
            return redirect()->route('dashboard');
        }

        $token = VerificationToken::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (! $token) {
            return back()->withErrors(['otp' => 'Galat ya expire ho gaya OTP. Dobara try karein.'])->withInput();
        }

        $token->update(['used' => true]);
        $user->update(['email_verified_at' => now()]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account verify ho gaya! Welcome.');
    }
}
