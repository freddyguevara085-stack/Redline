@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>Recursos y Biblioteca</h1>
      <p>Descubre, descarga y comparte materiales educativos de la comunidad.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('biblioteca.create') }}" class="btn-neo">Añadir recurso</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Explorar recursos</h2>
      <span class="tag">{{ $items->total() }} elementos</span>
    </div>
    <div class="filter-bar">
      <form method="GET" action="{{ route('biblioteca.index') }}">
        <input type="text" name="q" value="{{ $q }}" placeholder="Buscar recurso..." class="input search-input">
      </form>
      <a href="{{ route('biblioteca.create') }}" class="btn-ghost btn-compact">Nuevo recurso</a>
    </div>

    <div class="resource-grid">
      @forelse($items as $item)
        <article class="resource-card">
          <div class="resource-media">
            @if($item->cover_path)
              <img src="{{ asset('storage/'.ltrim($item->cover_path, '/')) }}" alt="Portada de {{ $item->title }}">
            @elseif($item->type==="image" && $item->file_path)
              <img src="{{ asset('storage/'.ltrim($item->file_path, '/')) }}" alt="Imagen biblioteca">
            @elseif($item->type==="video" && ($item->video_embed_url || $item->file_path))
              <span>Video</span>
            @elseif($item->type==="pdf" && $item->file_path)
              <span>PDF</span>
            @else
              <span>Recurso</span>
            @endif
          </div>
          <div class="resource-body">
            <h3>{{ $item->title }}</h3>
            <div class="resource-meta">
              <span>{{ ucfirst($item->type) }}</span>
              <span>{{ $item->author->name ?? 'Desconocido' }}</span>
            </div>
            <p>{{ Str::limit($item->description, 100) }}</p>
            <div class="resource-actions">
              <a href="{{ route('biblioteca.show',$item) }}" class="btn-neo btn-compact">Ver</a>
              <a href="{{ route('biblioteca.edit',$item) }}" class="btn-ghost btn-compact">Editar</a>
            </div>
          </div>
        </article>
      @empty
        <div class="resource-empty">No hay recursos aún.</div>
      @endforelse
    </div>

    <div class="pagination-shell">
      {{ $items->links() }}
    </div>
  </section>
</div>
@endsection