@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Nueva noticia</h1>
      <p>Comparte una actualización con todo el equipo.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('noticias.index') }}" class="btn-ghost">Volver al listado</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Detalles de la publicación</h2>
      <span class="tag">Borrador</span>
    </div>
    <form method="POST" action="{{ route('noticias.store') }}" enctype="multipart/form-data" class="form-panel">
      @include('noticias._form',['news'=>null])
    </form>
  </section>
</div>
@endsection