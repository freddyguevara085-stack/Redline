@extends('layouts.app')

@section('content')
<div class="section-block">
    <section class="glass-panel page-hero profile-hero">
        <div class="profile-identity">
            <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="profile-identity-text">
                <h1>{{ $user->name }}</h1>
                <p>{{ $user->email }}</p>
                <div class="profile-meta">
                    <span class="profile-chip">Miembro desde {{ $registered->format('d/m/Y') }}</span>
                    <span class="profile-chip">Quizzes: {{ $score->quizzes_taken ?? 0 }}</span>
                    <span class="profile-chip">Ranking: {{ $position ?? 'Sin ranking' }}</span>
                </div>
            </div>
        </div>
        <div class="metric-stack">
            <div class="metric-stack-item">
                <div class="metric-title">Puntaje total</div>
                <div class="metric-value">{{ $score->score ?? 0 }}</div>
            </div>
            <div class="metric-stack-item">
                <div class="metric-title">Quizzes realizados</div>
                <div class="metric-value">{{ $score->quizzes_taken ?? 0 }}</div>
            </div>
            <div class="metric-stack-item">
                <div class="metric-title">Ranking actual</div>
                <div class="metric-value">{{ $position ?? 'Sin ranking' }}</div>
            </div>
        </div>
    </section>

    <section class="glass-panel">
        <div class="section-heading">
            <h2>Insignias obtenidas</h2>
        </div>
        @if($badges->count())
                    <div class="badge-board">
                        @foreach($badges as $badge)
                            <article class="badge-card">
                                <img src="{{ $badge->icon }}" alt="{{ $badge->name }}">
                                <div>
                                    <strong>{{ $badge->name }}</strong>
                                    <p class="badge-subtext">Requiere {{ $badge->threshold }} pts</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
        @else
            <p class="empty-state">Aún no tienes insignias. Completa actividades para desbloquear tus primeras medallas.</p>
        @endif
    </section>

    <section class="glass-panel">
        <div class="section-heading">
            <h2>Logros destacados</h2>
        </div>
        <ul class="achievement-list">
            <li>
                <span class="achievement-label">Miembro desde</span>
                <span class="achievement-value">{{ $registered->format('d/m/Y') }}</span>
            </li>
            <li>
                <span class="achievement-label">Puntaje mayor</span>
                <span class="achievement-value">{{ $score->score ?? 0 }}</span>
            </li>
            <li>
                <span class="achievement-label">Quizzes completados</span>
                <span class="achievement-value">{{ $score->quizzes_taken ?? 0 }}</span>
            </li>
            <li>
                <span class="achievement-label">Ranking actual</span>
                <span class="achievement-value">{{ $position ?? 'Sin ranking' }}</span>
            </li>
        </ul>
    </section>
</div>
@endsection
