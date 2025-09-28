@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar juego</h1>
      <p>Actualiza la información y mantén la experiencia fresca.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.show', $game) }}" class="btn-ghost">Ver juego</a>
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>{{ $game->title }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('juegos.update',$game) }}" class="form-panel">
      @method('PUT')
      @include('juegos._form', ['game'=>$game])
    </form>
  </section>
</div>
@endsection