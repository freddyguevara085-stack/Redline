@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar opción</h1>
      <p>{{ $question->statement }}</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Opción #{{ $option->id }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('juegos.optionUpdate',[$game,$question,$option]) }}" class="form-panel">
      @csrf
      @method('PUT')
      <div class="field-block field-block--full">
        <label for="text" class="field-label">Texto</label>
        <input type="text" id="text" name="text" class="input" value="{{ old('text',$option->text) }}" required>
      </div>
      <label class="form-check">
        <input type="checkbox" name="is_correct" value="1" @checked(old('is_correct',$option->is_correct))>
        <span>Marcar como respuesta correcta</span>
      </label>
      <div class="form-actions">
        <button class="btn-neo">Guardar</button>
        <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Cancelar</a>
      </div>
    </form>
  </section>
</div>
@endsection