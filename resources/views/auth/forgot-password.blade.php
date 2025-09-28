<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-16 h-16" />
            </a>
        </x-slot>

        <h2>Recuperar contraseña</h2>
        <p>{{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>

        <!-- Session Status -->
        <x-auth-session-status class="form-feedback" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="form-feedback" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}" class="form-row">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="form-footer form-footer--end">
                <x-button>{{ __('Email Password Reset Link') }}</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
