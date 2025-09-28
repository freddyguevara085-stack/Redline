@extends('layouts.app')

@section('content')
<div class="section-block">
  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>{{ $historia->title }}</h2>
      <a href="{{ route('historia.index') }}" class="link-ghost">Volver al listado</a>
    </div>
    @if($historia->cover_path)
      <img src="{{ asset('storage/'.ltrim($historia->cover_path, '/')) }}" class="collection-cover" alt="Portada de {{ $historia->title }}">
    @endif
    @if($historia->video_embed_url)
      <div class="video-embed" style="margin-top: 1.5rem; border-radius: 1rem; overflow: hidden; position: relative; padding-bottom: 56.25%; height: 0; box-shadow: var(--shadow-md);">
        <iframe src="{{ $historia->video_embed_url }}" title="Video de {{ $historia->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"></iframe>
      </div>
    @endif
    <div class="collection-meta" style="margin-top: 1.1rem;">
      <span>{{ $historia->category->name ?? 'Sin categoría' }}</span>
      <span>por {{ $historia->author->name ?? 'Desconocido' }}</span>
      <span>{{ $historia->published_at?->format('d/m/Y') }}</span>
    </div>
    @if($historia->era || $historia->leading_figure)
      <div class="collection-tags" style="margin-top: 0.85rem;">
        @if($historia->era)
          <span class="pill pill--accent">{{ $historia->era }}</span>
        @endif
        @if($historia->leading_figure)
          <span class="pill pill--outline">{{ $historia->leading_figure }}</span>
        @endif
      </div>
    @endif
    @if($historia->excerpt)
      <p class="muted" style="font-style: italic;">{{ $historia->excerpt }}</p>
    @endif
    <div class="rich-content">
      {!! nl2br(e($historia->content)) !!}
    </div>
  </section>

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Comentarios</h2>
    </div>
    @auth
      <form method="POST" action="{{ route('historia.addComment', $historia) }}" class="form-row">
        @csrf
        <div class="form-group">
          <x-label for="comment" :value="'Añadir comentario'" />
          <textarea id="comment" name="body" class="input" rows="3" placeholder="Escribe un comentario..." required></textarea>
        </div>
        <div class="form-footer form-footer--end">
          <x-button>Comentar</x-button>
        </div>
      </form>
    @else
      <p class="empty-state">Inicia sesión para compartir tu opinión.</p>
    @endauth

    <div class="comment-thread">
      @forelse($historia->comments as $c)
        <article class="comment-card">
          <header>
            <strong>{{ $c->author->name ?? 'Anónimo' }}</strong>
            <span>{{ $c->created_at->diffForHumans() }}</span>
          </header>
          <p class="muted" style="margin: 0;">{{ $c->body }}</p>
        </article>
      @empty
        <p class="empty-state">No hay comentarios aún. Sé el primero en iniciar la conversación.</p>
      @endforelse
    </div>
  </section>
</div>
@endsection