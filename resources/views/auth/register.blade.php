<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-16 h-16" />
            </a>
        </x-slot>

        <h2>Crear una cuenta</h2>
        <p>Regístrate para acceder a quizzes, ranking y recursos exclusivos de historia.</p>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="form-feedback" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="form-row">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <x-label for="name" :value="'Nombre'" />
                <x-input id="name" type="text" name="name" :value="old('name')" required autofocus placeholder="Tu nombre" />
            </div>

            <!-- Email Address -->
            <div class="form-group">
                <x-label for="email" :value="'Correo electrónico'" />
                <x-input id="email" type="email" name="email" :value="old('email')" required placeholder="tucorreo@ejemplo.com" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <x-label for="password" :value="'Contraseña'" />
                <x-input id="password"
                         type="password"
                         name="password"
                         required autocomplete="new-password"
                         placeholder="Crea una contraseña" />
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <x-label for="password_confirmation" :value="'Confirmar contraseña'" />
                <x-input id="password_confirmation"
                         type="password"
                         name="password_confirmation" required placeholder="Repite tu contraseña" />
            </div>

            <div class="form-footer">
                <a class="link-ghost" href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
                <x-button>Registrarme</x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
