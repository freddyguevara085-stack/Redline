@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Nuevo evento</h1>
      <p>Dale vida al calendario con actividades épicas para la comunidad.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('calendario.index') }}" class="btn-ghost">Ver calendario</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Detalles del evento</h2>
      <span class="tag">Borrador</span>
    </div>
    <form method="POST" action="{{ route('calendario.store') }}" class="form-panel">
      @include('calendario._form', ['calendario'=>null])
    </form>
  </section>
</div>
@endsection