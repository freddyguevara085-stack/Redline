@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Editar evento</h1>
      <p>Ajusta la información para mantener a todos en sincronía.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('calendario.show', $calendario) }}" class="btn-ghost">Ver evento</a>
      <a href="{{ route('calendario.index') }}" class="btn-ghost">Volver al calendario</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>{{ $calendario->title }}</h2>
      <span class="tag">Edición</span>
    </div>
    <form method="POST" action="{{ route('calendario.update',$calendario) }}" class="form-panel">
      @method('PUT')
      @include('calendario._form', ['calendario'=>$calendario])
    </form>
  </section>
</div>
@endsection