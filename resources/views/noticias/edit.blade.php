@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar noticia</h1>
      <p>Ajusta los detalles antes de volver a publicar.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('noticias.show', $noticia) }}" class="btn-ghost">Ver noticia</a>
      <a href="{{ route('noticias.index') }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>{{ $noticia->title }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('noticias.update',$noticia) }}" enctype="multipart/form-data" class="form-panel">
      @method('PUT')
      @include('noticias._form',['news'=>$noticia])
    </form>
  </section>
</div>
@endsection