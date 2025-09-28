@extends('layouts.app')

@section('content')
<div class="section-block">
  <section class="glass-panel page-hero">
    <div>
      <span class="tag">Historias</span>
      <h1>Nueva historia</h1>
      <p>Documenta un acontecimiento histórico y compártelo con la comunidad.</p>
    </div>
  </section>

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Detalles de la historia</h2>
      <a href="{{ route('historia.index') }}" class="link-ghost">Volver al listado</a>
    </div>
    <form method="POST" action="{{ route('historia.store') }}" enctype="multipart/form-data">
      @include('historia._form', ['historia' => null, 'eras' => $eras, 'leadingFigures' => $leadingFigures])
    </form>
  </section>
</div>
@endsection