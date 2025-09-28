@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Nueva pregunta</h1>
      <p>{{ $game->title }} — enriquece el juego con más retos.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Enunciado</h2>
      <span class="tag">Pregunta</span>
    </div>
    <form method="POST" action="{{ route('juegos.questionStore',$game) }}" class="form-panel">
      @csrf
      <div class="field-block">
        <label for="statement" class="field-label">¿Cuál es la pregunta?</label>
        <input type="text" id="statement" name="statement" class="input" placeholder="Escribe el enunciado" required>
      </div>
      <div class="form-actions">
        <button class="btn-neo">Guardar</button>
        <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Cancelar</a>
      </div>
    </form>
  </section>
</div>
@endsection