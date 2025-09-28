@extends('layouts.app')

@section('content')
<div class="section-block">
  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>{{ $calendario->title }}</h2>
      @can('update', $calendario)
        <a href="{{ route('calendario.edit', $calendario) }}" class="link-ghost">Editar evento</a>
      @endcan
    </div>
    <div class="event-detail">
      <div class="event-detail-meta">
        <span class="event-date">Inicio {{ $calendario->start_at->format('d M · H:i') }}</span>
        @if($calendario->end_at)
          <span class="event-date">Termina {{ $calendario->end_at->format('d M · H:i') }}</span>
        @endif
        <span class="event-location">{{ $calendario->location ?: 'Ubicación por confirmar' }}</span>
      </div>
      <article class="rich-content">
        <p>{{ $calendario->description }}</p>
      </article>
      <p class="muted">Organizado por {{ $calendario->author->name ?? 'Equipo Redline' }}</p>
      <div class="collection-actions" style="margin-top: 1.2rem;">
        <a href="{{ route('calendario.index') }}" class="btn-ghost">Volver al calendario</a>
        @can('delete', $calendario)
          <form method="POST" action="{{ route('calendario.destroy', $calendario) }}" onsubmit="return confirm('¿Eliminar este evento?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-ghost" style="border-color: rgba(248, 113, 113, 0.4); color: #fca5a5;">Eliminar</button>
          </form>
        @endcan
      </div>
    </div>
  </section>
</div>
@endsection