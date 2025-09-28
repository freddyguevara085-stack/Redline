{{-- resources/views/juegos/play.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="section-block">
  @if ($errors->any())
    <x-auth-validation-errors :errors="$errors" class="alert-error" />
  @endif

  <div class="page-hero">
    <div>
      <h1>{{ $game->title }}</h1>
      @if($game->description)
        <p>{{ $game->description }}</p>
      @endif
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver a juegos</a>
    </div>
  </div>

  @php
    $questionList = $game->questions ?? collect();
  @endphp

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Preguntas</h2>
      <span class="tag">{{ $questionList->count() }} items</span>
    </div>
    <form method="POST" action="{{ route('juegos.submit',$game) }}" class="quiz-list">
      @csrf
  @forelse($questionList as $idx => $q)
        <article class="quiz-card">
          <header>
            <h3>#{{ $idx + 1 }} — {{ $q->statement }}</h3>
          </header>
          <div class="quiz-answers">
            @foreach($q->options as $o)
              <label class="form-check">
                <input type="radio" name="q{{ $q->id }}" value="{{ $o->id }}" required>
                <span>{{ $o->text }}</span>
              </label>
            @endforeach
          </div>
        </article>
      @empty
        <div class="resource-empty">Este juego aún no tiene preguntas.</div>
      @endforelse

      <div class="form-actions">
        <button class="btn-neo">Enviar respuestas</button>
      </div>
    </form>
  </section>
</div>
@endsection