@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Crear juego</h1>
      <p>Define la dinámica y comienza a sumar preguntas.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver al listado</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Configuración inicial</h2>
      <span class="tag">Borrador</span>
    </div>
    <form method="POST" action="{{ route('juegos.store') }}" class="form-panel">
      @include('juegos._form', ['game'=>null])
    </form>
  </section>
</div>
@endsection