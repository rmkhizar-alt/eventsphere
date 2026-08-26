<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-xl font-bold tracking-tight text-gray-900">Create your account</h1>
        <p class="mt-1 text-sm text-gray-500">
            Pehle se account hai?
            <a href="{{ route('login') }}" class="font-medium text-brand-600 transition hover:text-brand-500">
                Log in karo
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <x-ui.input label="Name" name="name" type="text" :value="old('name')"
                    required autofocus autocomplete="name" placeholder="Ali Khan" />

        <x-ui.input label="Email" name="email" type="email" :value="old('email')"
                    required autocomplete="username" placeholder="you@example.com" />

        <x-ui.input label="Password" name="password" type="password"
                    required autocomplete="new-password" hint="Kam az kam 8 characters." placeholder="••••••••" />

        <x-ui.input label="Confirm Password" name="password_confirmation" type="password"
                    required autocomplete="new-password" placeholder="••••••••" />

        <div class="flex items-center justify-end pt-2">
            <x-ui.button type="submit" data-animate-hover class="w-full">
                {{ __('Sign up') }}
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
