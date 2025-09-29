@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar pregunta</h1>
      <p>{{ $game->title }} — ajusta el enunciado.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Pregunta #{{ $question->id }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('juegos.questionUpdate',[$game,$question]) }}" class="form-panel">
      @csrf
      @method('PUT')
      <div class="field-block field-block--full">
        <label for="statement" class="field-label">Enunciado</label>
        <input type="text" id="statement" name="statement" class="input" value="{{ old('statement',$question->statement) }}" required>
      </div>
      <div class="form-actions">
        <button class="btn-neo">Guardar</button>
        <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Cancelar</a>
      </div>
    </form>
  </section>
</div>
@endsection