<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationToken;
use App\Mail\VerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);

        VerificationToken::create([
            'user_id' => $user->id,
            'token' => $otp,
            'used' => false,
            'expires_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $otp));

        return redirect()->route('verify-otp.form')
            ->with('email', $user->email)
            ->with('otp_code', $otp)
            ->with('success', 'OTP aapke email par bhej diya gaya hai.');
    }
}
