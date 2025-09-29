@extends('layouts.app')

@section('content')
@php
  $questionCount = $game->questions->count();
@endphp

<div class="section-block">
  @if ($errors->any())
    <x-auth-validation-errors :errors="$errors" class="alert-error" />
  @endif

  <div class="page-hero">
    <div>
      <h1>{{ $game->title }}</h1>
      <p>{{ $game->description }}</p>
    </div>
    <div class="hero-actions">
      @if($questionCount > 0)
        <a href="{{ route('juegos.play',$game) }}" class="btn-neo">Jugar ahora</a>
      @else
        <button class="btn-neo" type="button" disabled title="Agrega preguntas para activar el modo juego">Jugar ahora</button>
      @endif
      @can('manageQuestions', $game)
        <a href="{{ route('juegos.questions',$game) }}" class="btn-ghost">Administrar preguntas</a>
      @endcan
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Configuración</h2>
      <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
  <span class="tag">{{ $game->type_label }}</span>
        <span class="tag">{{ $game->points_per_question }} pts / pregunta</span>
        <span class="tag">{{ $questionCount }} preguntas</span>
        <span class="tag">{{ $game->plays_count ?? 0 }} partidas</span>
      </div>
    </div>
    @php
      $questionTotal = optional($game->questions)->count() ?? 0;
      $potentialScore = ($game->points_per_question ?? 0) * $questionTotal;
    @endphp
    <div class="event-detail">
      <p class="muted">Puntaje total potencial: {{ $potentialScore }} pts</p>
      @if($questionCount === 0)
        <div class="resource-empty" style="margin: 1rem 0;">
          Este juego aún no tiene preguntas.
          @can('manageQuestions', $game)
            <a href="{{ route('juegos.questionCreate', $game) }}" class="link-ghost">Agrega la primera</a> para habilitar el modo juego.
          @else
            Pide al autor que agregue preguntas para poder jugar.
          @endcan
        </div>
      @endif
      <div class="collection-actions">
        @can('update', $game)
          <a class="btn-ghost" href="{{ route('juegos.edit',$game) }}">Editar juego</a>
        @endcan
        <a class="btn-ghost" href="{{ route('juegos.index') }}">Volver a juegos</a>
      </div>
    </div>
  </section>
</div>
@endsection