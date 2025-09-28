@extends('layouts.app')
@section('content')
<style>
    .hero-redline {
        background: linear-gradient(90deg, #e0f2fe 0%, #f0fdf4 100%);
    }
    .dashboard-card {
        background: #fff;
        border-radius: 1.25rem;
        box-shadow: 0 4px 16px 0 rgba(36, 124, 242, .09), 0 1.5px 8px 0 rgba(0,0,0,0.04);
        transition: transform .18s cubic-bezier(.4,0,.2,1), box-shadow .18s;
        padding: 2.2rem 1.2rem 1.2rem 1.2rem;
        position: relative;
        overflow: hidden;
    }
    .dashboard-card:hover {
        transform: translateY(-5px) scale(1.025) rotate(-1deg);
        box-shadow: 0 10px 36px 0 #23c55f22, 0 4px 24px 0 #247cf233;
    }
    .dashboard-icon {
        position: absolute;
        top: -18px;
        right: -18px;
        opacity: 0.12;
        font-size: 5.5rem;
        pointer-events: none;
    }
    .dashboard-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: #247cf2;
        letter-spacing: 0.01em;
        margin-bottom: .5rem;
    }
    .dashboard-number {
        font-size: 2.7rem;
        font-weight: bold;
        color: #23c55f;
        line-height: 1.1;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 12px #23c55f33;
    }
    .dashboard-sub {
        font-size: 1rem;
        color: #64748b;
    }
    @media (prefers-color-scheme: dark) {
        .hero-redline {background: linear-gradient(90deg, #0f172a 0%, #052e16 100%);}
        .dashboard-card {background: #1e293b; color: #f1f5f9;}
        .dashboard-label {color: #60a5fa;}
        .dashboard-number {color: #4ade80;}
        .dashboard-sub {color: #94a3b8;}
    }
</style>

<div class="hero-redline rounded-3xl p-8 mb-8 flex flex-col md:flex-row items-center justify-between shadow-lg">
    <div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-blue-700 mb-2">Bienvenido a Redline Project</h1>
        <p class="text-lg text-gray-600 dark:text-gray-300 mb-2">Tu plataforma educativa y cultural para estudiantes. ¡Explora el conocimiento con energía positiva!</p>
    </div>
    <img src="https://cdn-icons-png.flaticon.com/512/3176/3176356.png" alt="Dashboard" class="h-28 w-28 md:h-36 md:w-36 hidden md:block animate-bounce">
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">
    <div class="dashboard-card group relative">
        <div class="dashboard-label">Eventos</div>
        <div class="dashboard-number group-hover:scale-105 transition">{{ $eventsCount }}</div>
        <div class="dashboard-sub">Próximas actividades</div>
        <div class="dashboard-icon">📅</div>
    </div>
    <div class="dashboard-card group relative">
        <div class="dashboard-label">Historias</div>
        <div class="dashboard-number group-hover:scale-105 transition">{{ $histCount }}</div>
        <div class="dashboard-sub">Recuerdos compartidos</div>
        <div class="dashboard-icon">📖</div>
    </div>
    <div class="dashboard-card group relative">
        <div class="dashboard-label">Recursos</div>
        <div class="dashboard-number group-hover:scale-105 transition">{{ $libCount }}</div>
        <div class="dashboard-sub">Biblioteca digital</div>
        <div class="dashboard-icon">📚</div>
    </div>
    <div class="dashboard-card group relative">
        <div class="dashboard-label">Usuarios</div>
        <div class="dashboard-number group-hover:scale-105 transition">{{ $users }}</div>
        <div class="dashboard-sub">Estudiantes y docentes</div>
        <div class="dashboard-icon">👥</div>
    </div>
</div>

<div class="mt-10 grid grid-cols-1 md:grid-cols-2 gap-8">
    <div class="rounded-2xl bg-white dark:bg-slate-900 shadow-lg p-7 flex items-center gap-4 hover:shadow-2xl transition">
        <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-4 mr-3">
            <img src="https://cdn-icons-png.flaticon.com/512/3176/3176297.png" alt="Juegos" class="w-10 h-10">
        </div>
        <div>
            <div class="text-xl font-bold text-blue-700 dark:text-green-300">Juega y aprende</div>
            <div class="text-gray-600 dark:text-gray-400">Participa en juegos interactivos y gana medallas.</div>
        </div>
    </div>
    <div class="rounded-2xl bg-white dark:bg-slate-900 shadow-lg p-7 flex items-center gap-4 hover:shadow-2xl transition">
        <div class="bg-green-100 dark:bg-green-900 rounded-full p-4 mr-3">
            <img src="https://cdn-icons-png.flaticon.com/512/3176/3176296.png" alt="Ranking" class="w-10 h-10">
        </div>
        <div>
            <div class="text-xl font-bold text-green-700 dark:text-blue-300">Ranking y logros</div>
            <div class="text-gray-600 dark:text-gray-400">Consulta los mejores puntajes y compite sanamente.</div>
        </div>
    </div>
</div>
@endsection