<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold tracking-tight text-gray-900">Email Verify Karo</h1>
        <p class="mt-1 text-sm text-gray-500">
            Aapke email par OTP bheja gaya hai. Woh 6-digit code neeche dalein.
        </p>
    </div>

    @if (session('success'))
        <x-ui.alert variant="success" class="mb-4" :dismissible="true">
            {{ session('success') }}
        </x-ui.alert>
    @endif

    @if (session('otp_code'))
        <div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-center">
            <p class="text-xs font-medium text-amber-600 uppercase tracking-wide mb-1">Dev Mode — Aapka OTP Code</p>
            <p class="text-3xl font-bold tracking-[0.3em] text-amber-800">{{ session('otp_code') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <x-ui.alert variant="error" class="mb-4" :dismissible="true">
            {{ $errors->first() }}
        </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('verify-otp.submit') }}" class="space-y-4">
        @csrf

        <x-ui.input label="Email" name="email" type="email" :value="$email ?? old('email')"
                    required autofocus placeholder="you@example.com" />

        <x-ui.input label="OTP Code (6 digits)" name="otp" type="text"
                    required maxlength="6" pattern="[0-9]{6}" placeholder="123456" />

        <div class="flex items-center justify-end pt-2">
            <x-ui.button type="submit" data-animate-hover class="w-full">
                Verify Account
            </x-ui.button>
        </div>
    </form>

    <p class="mt-4 text-center text-sm text-gray-500">
        OTP nahi mila?
        <a href="{{ route('register') }}" class="font-medium text-brand-600 hover:text-brand-500">
            Dobara signup karein
        </a>
    </p>
</x-guest-layout>
