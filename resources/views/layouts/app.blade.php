<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700&display=swap">

    <!-- Assets -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    </head>
    <body>
        <div class="app-shell">
            @include('layouts.navigation')

            @isset($header)
                <header class="app-surface">
                    <div class="page-shell">
                        <div class="glass-panel" style="padding: 1.8rem 2rem;">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <main class="app-surface">
                <div class="page-shell">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
