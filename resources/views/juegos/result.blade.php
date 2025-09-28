@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Resultados</h1>
      <p>Revisa tu desempeño y vuelve a intentarlo para mejorar.</p>
    </div>
    <div class="hero-actions">
      @isset($game)
        <a href="{{ route('juegos.play', $game) }}" class="btn-ghost">Reintentar</a>
      @endisset
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver al listado</a>
    </div>
  </div>

  <section class="glass-panel" style="text-align: center; padding: 3rem 2.5rem;">
    <h2 class="title" style="margin-bottom: 1.5rem;">¡Excelente trabajo!</h2>
    <p class="muted">Preguntas correctas</p>
    <p style="font-size: 2.4rem; font-weight: 700; margin-bottom: 1rem;">{{ $correct }}<span style="font-size: 1rem; opacity: 0.7;"> / {{ $total }}</span></p>
    <p class="muted">Puntaje total</p>
    <p style="font-size: 2rem; font-weight: 700; color: rgba(34, 211, 238, 0.92);">{{ $score }}</p>
    <div class="collection-actions" style="justify-content: center; margin-top: 2rem;">
      @isset($game)
        <a href="{{ route('juegos.play', $game) }}" class="btn-neo">Jugar de nuevo</a>
      @endisset
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Explorar juegos</a>
    </div>
  </section>
</div>
@endsection