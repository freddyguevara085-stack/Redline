<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-16 h-16" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="form-feedback" :errors="$errors" />

        <h2>{{ __('Reset Password') }}</h2>
        <p>Ingresa tu nueva contraseña para recuperar acceso a tu cuenta.</p>

        <form method="POST" action="{{ route('password.update') }}" class="form-row">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group">
                <x-label for="email" :value="__('Email')" />

                <x-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <x-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-input id="password_confirmation"
                         type="password"
                         name="password_confirmation" required />
            </div>

            <div class="form-footer form-footer--end">
                <x-button>{{ __('Reset Password') }}</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
