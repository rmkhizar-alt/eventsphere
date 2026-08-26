<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold tracking-tight text-gray-900">Welcome back</h1>
        <p class="mt-1 text-sm text-gray-500">
            Naye hain?
            <a href="{{ route('register') }}" class="font-medium text-brand-600 transition hover:text-brand-500">
                Account banao
            </a>
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <x-ui.alert variant="success" class="mb-4" :dismissible="true">
            {{ session('status') }}
        </x-ui.alert>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <x-ui.input label="Email" name="email" type="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="you@example.com" />

        <x-ui.input label="Password" name="password" type="password"
                    required autocomplete="current-password" placeholder="••••••••" />

        <!-- Remember Me -->
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox"
                   class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500"
                   name="remember">
            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>

        <div class="flex items-center justify-between pt-2">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-gray-600 transition hover:text-brand-600">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-ui.button type="submit" data-animate-hover>
                {{ __('Log in') }}
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
