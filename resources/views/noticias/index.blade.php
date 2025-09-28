@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero page-hero--split">
    <div>
      <h1>Noticias</h1>
      <p>Infórmate sobre novedades, logros y actividades educativas de la comunidad.</p>
      <div class="hero-stats">
        <div class="hero-stat">
          <span>Total publicadas</span>
          <strong>{{ $news->total() }}</strong>
        </div>
        @php
          $latest = $news->first();
        @endphp
        <div class="hero-stat">
          <span>Última actualización</span>
          <strong>{{ $latest?->published_at?->format('d M') ?? 'Sin fecha' }}</strong>
        </div>
      </div>
    </div>
    <aside class="hero-cta-card">
      <span class="cta-eyebrow">Comparte una novedad</span>
      <h3>¿Tienes algo que contar?</h3>
      <p>Destaca logros, próximos eventos o historias inspiradoras para mantener informada a la comunidad.</p>
      <a href="{{ route('noticias.create') }}" class="btn-pulse">Publicar noticia</a>
    </aside>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Últimas novedades</h2>
      <span class="tag">{{ $news->total() }} artículos</span>
    </div>
    <div class="filter-bar">
      <form method="GET" action="{{ route('noticias.index') }}">
        <input type="text" name="q" value="{{ $q }}" placeholder="Buscar noticia..." class="input search-input">
      </form>
      <a href="{{ route('noticias.create') }}" class="btn-ghost btn-compact">Nueva noticia</a>
    </div>

    <div class="resource-grid">
      @forelse($news as $n)
        <article class="resource-card">
          <div class="resource-media">
            @if($n->cover_path)
              <img src="{{ asset('storage/'.$n->cover_path) }}" alt="Portada de {{ $n->title }}">
            @else
              <span>News</span>
            @endif
          </div>
          <div class="resource-body">
            <h3>{{ $n->title }}</h3>
            <div class="resource-meta">
              <span>{{ $n->published_at?->format('d/m/Y') ?? 'Borrador' }}</span>
            </div>
            <p>{{ Str::limit($n->body, 160) }}</p>
            <div class="resource-actions">
              <a href="{{ route('noticias.show',$n) }}" class="btn-neo btn-compact">Ver</a>
              <a href="{{ route('noticias.edit',$n) }}" class="btn-ghost btn-compact">Editar</a>
            </div>
          </div>
        </article>
      @empty
        <div class="resource-empty">No hay noticias aún.</div>
      @endforelse
    </div>

    <div class="pagination-shell">
      {{ $news->links() }}
    </div>
  </section>
</div>
@endsection