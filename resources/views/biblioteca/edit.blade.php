@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar recurso</h1>
      <p>Actualiza la información para mantener la biblioteca al día.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('biblioteca.show', $item) }}" class="btn-ghost">Ver recurso</a>
      <a href="{{ route('biblioteca.index') }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>{{ $item->title }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('biblioteca.update', $item) }}" enctype="multipart/form-data" class="form-panel">
      @method('PUT')
      @include('biblioteca._form', ['item'=>$item])
    </form>
  </section>
</div>
@endsection