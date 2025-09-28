@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="section-block">
  <section class="glass-panel page-hero">
    <div>
      <span class="tag">Agenda de Redline</span>
      <h1>Eventos y calendario</h1>
      <p>No te pierdas ninguna clase, charla o actividad especial de la comunidad.</p>
      <div class="hero-actions">
        <a href="{{ route('calendario.create') }}" class="btn-neo">Nuevo evento</a>
        <a href="{{ route('historia.index') }}" class="btn-ghost">Explorar historias</a>
      </div>
    </div>
    <div class="panel-solid" style="padding: 2rem; display: grid; gap: 1rem;">
      <div>
        <div class="metric-title">Eventos programados</div>
        <div class="metric-value" style="font-size: 2.1rem;">{{ $events->count() }}</div>
      </div>
      <p class="muted">Organiza o participa para mantener viva la agenda histórica de la comunidad.</p>
    </div>
  </section>

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Agenda programada</h2>
      <a href="{{ route('calendario.create') }}" class="link-ghost">Agregar evento</a>
    </div>
    <ul class="event-list">
      @forelse($events as $event)
        <li class="event-card">
          <span class="event-date">{{ $event->start_at->format('d M · H:i') }}</span>
          <h3>{{ $event->title }}</h3>
          <p>{{ Str::limit($event->description, 150) }}</p>
          <div class="event-meta">
            <span>{{ $event->location ?: 'Ubicación por confirmar' }}</span>
            @if($event->end_at)
              <span>Finaliza {{ $event->end_at->format('d M · H:i') }}</span>
            @endif
            <span>Organiza {{ $event->author->name ?? 'Equipo Redline' }}</span>
          </div>
          <div class="collection-actions">
            <a href="{{ route('calendario.show', $event) }}" class="btn-neo" style="padding: 0.55rem 1.35rem;">Detalles</a>
            <a href="{{ route('calendario.edit', $event) }}" class="btn-ghost" style="padding: 0.55rem 1.35rem;">Editar</a>
          </div>
        </li>
      @empty
        <li class="empty-state">No hay eventos programados todavía. ¡Crea el primero para iniciar la agenda!</li>
      @endforelse
    </ul>
  </section>

  @if($events instanceof \Illuminate\Contracts\Pagination\Paginator || $events instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
    <div class="glass-panel" style="padding: 1.4rem 1.8rem;">
      {{ $events->withQueryString()->links() }}
    </div>
  @endif
</div>
@endsection