@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>{{ $item->title }}</h1>
      <p>{{ ucfirst($item->type) }} compartido por {{ $item->author->name ?? 'la comunidad' }}.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('biblioteca.edit', $item) }}" class="btn-ghost">Editar</a>
      <a href="{{ route('biblioteca.index') }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel article-surface">
    <div class="section-heading">
      <h2>Detalles</h2>
      <span class="tag">{{ ucfirst($item->type) }}</span>
    </div>
    @if($item->cover_path)
      <figure class="resource-cover" style="margin-bottom: 1.5rem;">
        <img src="{{ asset('storage/'.ltrim($item->cover_path, '/')) }}" alt="Portada de {{ $item->title }}" style="width: 100%; border-radius: 18px; box-shadow: var(--shadow-md);">
        @if($item->video_caption && $item->type !== 'video')
          <figcaption class="muted" style="margin-top: 0.75rem;">{{ $item->video_caption }}</figcaption>
        @endif
      </figure>
    @endif
    @if($item->description)
      <article class="article-content">
        <p>{{ $item->description }}</p>
      </article>
    @endif

    <div class="article-cover">
      @if($item->type==="image" && $item->file_path)
        <img src="{{ asset('storage/'.ltrim($item->file_path, '/')) }}" alt="{{ $item->title }}">
      @elseif($item->type==="pdf" && $item->file_path)
        <iframe src="{{ asset('storage/'.ltrim($item->file_path, '/')) }}" style="width: 100%; height: 480px; border: none;"></iframe>
      @elseif($item->type==="video" && $item->video_embed_url)
        <div class="video-embed" style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 18px; box-shadow: var(--shadow-md);">
          <iframe src="{{ $item->video_embed_url }}" title="Video {{ $item->title }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy" style="position: absolute; top:0; left:0; width:100%; height:100%; border:0;"></iframe>
        </div>
        @if($item->video_caption)
          <p class="muted" style="margin-top: 0.75rem;">{{ $item->video_caption }}</p>
        @endif
      @elseif($item->type==="video" && $item->file_path)
        <video controls style="width: 100%; height: 420px; border-radius: 22px;">
          <source src="{{ asset('storage/'.ltrim($item->file_path, '/')) }}">
        </video>
        @if($item->video_caption)
          <p class="muted" style="margin-top: 0.75rem;">{{ $item->video_caption }}</p>
        @endif
      @elseif($item->type==="link" && $item->external_url)
        <div class="resource-empty" style="margin: 1.2rem 0;">
          <a href="{{ $item->external_url }}" target="_blank" class="btn-neo">Abrir enlace externo</a>
        </div>
      @else
        <div class="resource-empty">Este recurso no tiene vista previa disponible.</div>
      @endif
    </div>
  </section>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>Comentarios</h2>
      <span class="tag">Feedback</span>
    </div>
    @auth
      <form method="POST" action="{{ route('biblioteca.addComment', $item) }}" class="form-panel" style="margin-bottom: 1.5rem;">
        @csrf
        <div class="field-block field-block--full">
          <label class="field-label" for="comment">Comparte tu opinión</label>
          <textarea id="comment" name="body" class="input" rows="3" placeholder="Escribe un comentario..." required></textarea>
        </div>
        <div class="form-actions">
          <button class="btn-neo">Comentar</button>
        </div>
      </form>
    @else
      <p class="muted">Inicia sesión para comentar.</p>
    @endauth

    <div class="comment-board">
      @forelse($item->comments as $c)
        <div class="comment-card">
          <div class="comment-meta">
            <strong>{{ $c->author->name ?? 'Anónimo' }}</strong>
            <span>{{ $c->created_at->diffForHumans() }}</span>
          </div>
          <p>{{ $c->body }}</p>
        </div>
      @empty
        <div class="resource-empty">No hay comentarios aún.</div>
      @endforelse
    </div>
  </section>
</div>
@endsection