<nav>
    <div class="nav-bar-left">
        <a href="{{ route('dashboard') }}" class="brand-link" aria-label="Ir al panel de {{ config('app.name', 'Redline') }}">
            <span class="brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo de {{ config('app.name', 'Redline') }}">
            </span>
            <span class="brand-text">{{ config('app.name', 'Redline') }}</span>
        </a>

        <div class="nav-group">
        <a href="{{ route('dashboard') }}" class="nav-link{{ request()->routeIs('dashboard') ? ' active' : '' }}">Dashboard</a>
        <a href="{{ route('historia.index') }}" class="nav-link{{ request()->routeIs('historia.*') ? ' active' : '' }}">Historia</a>
        <a href="{{ route('biblioteca.index') }}" class="nav-link{{ request()->routeIs('biblioteca.*') ? ' active' : '' }}">Biblioteca</a>
        <div class="nav-dropdown">
            <a href="{{ route('juegos.index') }}" class="nav-link{{ request()->routeIs('juegos.*') || request()->routeIs('quiz.*') ? ' active' : '' }}">
                Juegos
            </a>
            <div class="nav-dropdown-menu">
                <a href="{{ route('juegos.index') }}" class="nav-sublink{{ request()->routeIs('juegos.*') ? ' active' : '' }}">Catálogo</a>
                <a href="{{ route('quiz.index') }}" class="nav-sublink{{ request()->routeIs('quiz.*') ? ' active' : '' }}">Quizzes</a>
            </div>
        </div>
        <a href="{{ route('noticias.index') }}" class="nav-link{{ request()->routeIs('noticias.*') ? ' active' : '' }}">Noticias</a>
        <a href="{{ route('calendario.index') }}" class="nav-link{{ request()->routeIs('calendario.*') ? ' active' : '' }}">Calendario</a>
        <a href="{{ route('ranking.index') }}" class="nav-link{{ request()->routeIs('ranking.*') ? ' active' : '' }}">Ranking</a>
        </div>
    </div>

    <div class="nav-meta">
        @auth
            <a href="{{ route('profile.show') }}" class="nav-link{{ request()->routeIs('profile.*') ? ' active' : '' }}">Perfil</a>
            <span class="badge-chip">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="nav-link{{ request()->routeIs('login') ? ' active' : '' }}">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="nav-link{{ request()->routeIs('register') ? ' active' : '' }}">Registrarse</a>
        @endauth
    </div>
</nav>
