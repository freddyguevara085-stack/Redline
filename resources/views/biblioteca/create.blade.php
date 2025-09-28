@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Nuevo recurso</h1>
      <p>Comparte material valioso con toda la comunidad Redline.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('biblioteca.index') }}" class="btn-ghost">Volver al catálogo</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Detalles del recurso</h2>
      <span class="tag">Formulario</span>
    </div>
    <form method="POST" action="{{ route('biblioteca.store') }}" enctype="multipart/form-data" class="form-panel">
      @include('biblioteca._form', ['item'=>null])
    </form>
  </section>
</div>
@endsection