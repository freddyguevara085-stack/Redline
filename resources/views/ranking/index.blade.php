@extends('layouts.app')

@section('content')
<div class="section-block">
    <div class="page-hero">
        <div>
            <h1>🏆 Ranking Gamificado</h1>
            <p>Revisa quién lidera la tabla y motiva a tu equipo a subir puestos.</p>
        </div>
        <div class="hero-actions">
            <a href="{{ route('juegos.index') }}" class="btn-neo">Ir a juegos</a>
        </div>
    </div>

    <section class="glass-panel">
        <div class="section-heading">
            <h2>Tabla de posiciones</h2>
            <span class="tag">Top {{ count($ranking) }}</span>
        </div>

        <div class="scoreboard">
            @foreach($ranking as $index => $r)
                <div class="score-row {{ $index < 3 ? 'highlight' : '' }}">
                    <div class="score-position">#{{ $index+1 }}</div>
                    <div>
                        <strong>{{ $r->user->name ?? 'Anónimo' }}</strong>
                        <div class="muted">{{ $r->games_played }} juegos jugados</div>
                    </div>
                    <div class="score-points">{{ $r->score }} pts</div>
                    <div class="muted">Puntaje promedio: {{ number_format($r->average_score ?? 0, 1) }}</div>
                    <div class="score-medal">
                        @if($index==0)
                            🥇
                        @elseif($index==1)
                            🥈
                        @elseif($index==2)
                            🥉
                        @else
                            🎖️
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection