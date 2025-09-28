<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-16 h-16" />
            </a>
        </x-slot>

        <h2>Iniciar sesión</h2>
        <p>Accede a tu cuenta para participar en el portal educativo de historia.</p>

        <!-- Session Status -->
        <x-auth-session-status class="form-feedback" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="form-feedback" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="form-row">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-label for="email" :value="'Correo electrónico'" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="tucorreo@ejemplo.com" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-label for="password" :value="'Contraseña'" />
                <x-input id="password"
                         type="password"
                         name="password"
                         required autocomplete="current-password"
                         placeholder="Tu contraseña" />
            </div>

            <!-- Remember Me -->
            <label for="remember_me" class="form-remember">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Recordarme</span>
            </label>

            <div class="form-footer">
                @if (Route::has('password.request'))
                    <a class="link-ghost" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                @endif
                <x-button>Ingresar</x-button>
            </div>

            <div class="auth-links">
                <span>¿No tienes cuenta?</span>
                <a href="{{ route('register') }}">Regístrate aquí</a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
