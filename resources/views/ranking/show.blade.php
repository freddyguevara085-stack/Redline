@extends('layouts.app')

@section('content')
<div class="section-block">
  <div class="page-hero">
    <div>
      <h1>{{ $historia->title }}</h1>
      <p>Resumen de logros y méritos alcanzados.</p>
    </div>
    <div class="hero-actions">
      <a href="{{ route('ranking.index') }}" class="btn-ghost">Volver al ranking</a>
    </div>
  </div>

  <section class="glass-panel article-surface">
    @if($historia->cover_path)
      <div class="article-cover">
        <img src="{{ asset('storage/'.$historia->cover_path) }}" alt="{{ $historia->title }}">
      </div>
    @endif

    <article class="article-content">
      {!! nl2br(e($historia->content)) !!}
    </article>
  </section>
</div>
@endsection
