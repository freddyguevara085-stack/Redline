@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>{{ $noticia->title }}</h1>
      <p>{{ $noticia->published_at?->format('d M, Y') ?? 'Borrador sin publicar' }}</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('noticias.edit', $noticia) }}" class="btn-ghost">Editar</a>
      <a href="{{ route('noticias.index') }}" class="btn-ghost">Volver</a>
    </div>
  </div>

  <section class="glass-panel article-surface">
    @if($noticia->cover_path)
      <div class="article-cover">
        <img src="{{ asset('storage/'.$noticia->cover_path) }}" alt="{{ $noticia->title }}">
      </div>
    @endif
    <article class="article-content">
      {!! collect(preg_split("/(\r?\n){2,}/", trim($noticia->body)))->filter()->map(fn($paragraph)=>'<p>'.e(trim($paragraph)).'</p>')->implode("\n") !!}
    </article>
  </section>
</div>
@endsection