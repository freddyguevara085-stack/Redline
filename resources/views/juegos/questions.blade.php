@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Preguntas de {{ $game->title }}</h1>
      <p>Gestiona las opciones y asegura una experiencia dinámica.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.questionCreate',$game) }}" class="btn-neo">Agregar pregunta</a>
      <a href="{{ route('juegos.show',$game) }}" class="btn-ghost">Ver juego</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Banco de preguntas</h2>
      <span class="tag">{{ $game->questions->count() }} items</span>
    </div>

    <div class="quiz-list">
      @forelse($game->questions as $index => $q)
        <article class="quiz-card">
          <header>
            <h3>#{{ $index + 1 }} — {{ $q->statement }}</h3>
            <div class="resource-actions">
              <a href="{{ route('juegos.questionEdit',[$game,$q]) }}" class="btn-ghost btn-compact">Editar</a>
              <form action="{{ route('juegos.questionDestroy',[$game,$q]) }}" method="POST" onsubmit="return confirm('¿Eliminar pregunta?');">
                @csrf
                @method('DELETE')
                <button class="btn-danger btn-compact" type="submit">Eliminar</button>
              </form>
            </div>
          </header>
          <div>
            <strong class="muted">Opciones</strong>
            <div class="quiz-answers">
              @foreach($q->options as $o)
                <div class="resource-actions" style="justify-content: space-between; align-items: center;">
                  <span class="option-chip {{ $o->is_correct ? 'is-correct' : '' }}">{{ $o->text }}</span>
                  <div class="resource-actions">
                    <a href="{{ route('juegos.optionEdit',[$game,$q,$o]) }}" class="btn-ghost btn-compact">Editar</a>
                    <form action="{{ route('juegos.optionDestroy',[$game,$q,$o]) }}" method="POST" onsubmit="return confirm('¿Eliminar opción?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn-danger btn-compact" type="submit">Eliminar</button>
                    </form>
                  </div>
                </div>
              @endforeach
            </div>
            <a href="{{ route('juegos.optionCreate',[$game,$q]) }}" class="btn-ghost btn-compact" style="margin-top: 0.8rem;">Agregar opción</a>
          </div>
        </article>
      @empty
        <div class="resource-empty">Este juego aún no tiene preguntas.</div>
      @endforelse
    </div>
  </section>

  <div class="collection-actions">
    <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver a juegos</a>
  </div>
</div>
@endsection