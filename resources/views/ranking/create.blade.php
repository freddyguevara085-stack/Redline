@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Nueva historia</h1>
      <p>Documenta logros o hitos destacados para el ranking.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('ranking.index') }}" class="btn-ghost">Volver al ranking</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Detalles</h2>
      <span class="tag">Historia</span>
    </div>
    <form method="POST" action="{{ route('historia.store') }}" enctype="multipart/form-data" class="form-panel">
      @csrf
      <div class="field-block">
        <label class="field-label" for="title">Título</label>
        <input type="text" id="title" name="title" class="input" placeholder="Título" required>
      </div>
      <div class="field-block">
        <label class="field-label" for="excerpt">Extracto</label>
        <textarea id="excerpt" name="excerpt" class="input" placeholder="Resumen breve"></textarea>
      </div>
      <div class="field-block">
        <label class="field-label" for="content">Contenido</label>
        <textarea id="content" name="content" class="input" placeholder="Contenido completo" rows="6"></textarea>
      </div>
      <div class="field-block">
        <label class="field-label" for="cover">Imagen de portada</label>
        <input type="file" id="cover" name="cover" class="input">
      </div>
      <div class="form-actions">
        <button class="btn-neo">Guardar</button>
        <a href="{{ route('ranking.index') }}" class="btn-ghost">Cancelar</a>
      </div>
    </form>
  </section>
</div>
@endsection
