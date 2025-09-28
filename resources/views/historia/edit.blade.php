@extends('layouts.app')

@section('content')
<div class="section-block">
  <section class="glass-panel page-hero">
    <div>
      <span class="tag">Historias</span>
      <h1>Editar historia</h1>
      <p>Actualiza los detalles de <strong>{{ $historia->title }}</strong> y mantén la información al día.</p>
    </div>
  </section>

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Información principal</h2>
      <a href="{{ route('historia.index') }}" class="link-ghost">Volver al listado</a>
    </div>
    <form method="POST" action="{{ route('historia.update', $historia) }}" enctype="multipart/form-data">
      @method('PUT')
      @include('historia._form', ['historia' => $historia, 'eras' => $eras, 'leadingFigures' => $leadingFigures])
    </form>
  </section>
</div>
@endsection