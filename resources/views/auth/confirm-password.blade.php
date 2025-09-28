<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-16 h-16" />
            </a>
        </x-slot>

        <h2>{{ __('Confirm Password') }}</h2>
        <p>{{ __('This is a secure area of the application. Please confirm your password before continuing.') }}</p>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="form-feedback" :errors="$errors" />

        <form method="POST" action="{{ route('password.confirm') }}" class="form-row">
            @csrf

            <!-- Password -->
            <div class="form-group">
                <x-label for="password" :value="__('Password')" />

                <x-input id="password"
                         type="password"
                         name="password"
                         required autocomplete="current-password" />
            </div>

            <div class="form-footer form-footer--end">
                <x-button>{{ __('Confirm') }}</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
