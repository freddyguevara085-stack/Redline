@extends('layouts.app')

@section('content')
<div class="section-block">
  <section class="glass-panel page-hero page-hero--split">
    <div>
      <span class="tag">Colección histórica</span>
      <h1>Historias y recuerdos</h1>
      <p>Explora, comparte y revive episodios clave de nuestra historia comunitaria.</p>
      <div class="hero-actions">
        <a href="{{ route('historia.create') }}" class="btn-neo">Nueva historia</a>
        <a href="{{ route('biblioteca.index') }}" class="btn-ghost">Explorar biblioteca</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat">
          <span>Total publicadas</span>
          <strong>{{ $totalStories }}</strong>
        </div>
        <div class="hero-stat">
          <span>Última actualización</span>
          <strong>{{ $spotlightStory?->updated_at?->format('d M') ?? 'Sin fecha' }}</strong>
        </div>
      </div>
    </div>
    <aside class="hero-spotlight-card" @if($spotlightStory?->cover_url) style="--spotlight-cover: url('{{ $spotlightStory->cover_url }}');" @endif>
      <div class="hero-spotlight-card__overlay">
        <span class="cta-eyebrow">Destacada automática</span>
        @if($spotlightStory)
          <h3>{{ $spotlightStory->title }}</h3>
          <p>{{ \Illuminate\Support\Str::limit($spotlightStory->excerpt ?? strip_tags($spotlightStory->content), 140) }}</p>
          <div class="hero-spotlight-card__meta">
            @if($spotlightStory->era)
              <span class="pill pill--accent">{{ $spotlightStory->era }}</span>
            @endif
            @if($spotlightStory->leading_figure)
              <span class="pill">{{ $spotlightStory->leading_figure }}</span>
            @endif
          </div>
          <div class="hero-actions" style="margin-top: 0.6rem;">
            <a href="{{ route('historia.show', $spotlightStory) }}" class="btn-pulse">Ver detalle</a>
            <a href="{{ route('historia.create') }}" class="btn-ghost btn-compact">Agregar historia</a>
          </div>
        @else
          <h3>¿Tienes un nuevo episodio para la colección?</h3>
          <p>Actualiza tu archivo con nuevas perspectivas históricas y comparte tus hallazgos con la comunidad.</p>
          <div class="hero-actions" style="margin-top: 0.6rem;">
            <a href="{{ route('historia.create') }}" class="btn-pulse">Publicar historia</a>
            <a href="{{ route('historia.index') }}" class="btn-ghost btn-compact">Ver todas</a>
          </div>
        @endif
      </div>
    </aside>
  </section>

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Buscar historias</h2>
    </div>
    <form method="GET" action="{{ route('historia.index') }}" class="filter-form">
      <div class="filter-form__grid">
        <div class="filter-form__field form-group">
          <x-label for="q" :value="'Palabra clave'" />
          <div class="filter-form__inline">
            <x-input id="q" type="text" name="q" value="{{ $q }}" placeholder="Buscar título, extracto o contenido" />
            <button type="submit" class="btn-neo btn-compact">Aplicar</button>
          </div>
        </div>

        <div class="filter-form__field form-group">
          <x-label for="eraFilter" :value="'Época histórica'" />
          <select id="eraFilter" class="input" name="era">
            <option value="">Todas las épocas</option>
            @foreach($eras as $era)
              <option value="{{ $era }}" @selected($selectedEra === $era)>{{ $era }}</option>
            @endforeach
          </select>
        </div>

        <div class="filter-form__field form-group">
          <x-label for="protagonist" :value="'Protagonista'" />
          <select id="protagonist" class="input" name="protagonist">
            <option value="">Todos los referentes</option>
            @foreach($leadingFigures as $figure)
              <option value="{{ $figure }}" @selected($selectedProtagonist === $figure)>{{ $figure }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="filter-form__actions">
        <button type="submit" class="btn-neo">Filtrar historias</button>
        @if($activeFilters->isNotEmpty())
          <a href="{{ route('historia.index') }}" class="btn-ghost btn-compact">Limpiar filtros</a>
        @endif
      </div>
    </form>

    @if($activeFilters->isNotEmpty())
      <div class="filter-chips">
        @foreach($activeFilters as $label => $value)
          <span class="chip"><strong>{{ $label }}:</strong> {{ $value }}</span>
        @endforeach
      </div>
    @endif
  </section>

  @if($featuredStories->isNotEmpty())
    <section class="glass-panel section-block">
      <div class="section-heading">
        <h2>Destacados automáticos</h2>
      </div>
      <div class="featured-grid">
        @foreach($featuredStories as $featured)
          <article class="featured-card">
            <div class="featured-card__media">
              @if($featured->cover_url)
                <img src="{{ $featured->cover_url }}" alt="Portada de {{ $featured->title }}">
              @else
                <div class="featured-card__placeholder">
                  <span>{{ mb_strtoupper(mb_substr($featured->title, 0, 1)) }}</span>
                </div>
              @endif
            </div>
            <div class="featured-card__content">
              <div class="featured-card__meta">
                <span class="featured-card__chip">
                  {{ $featured->era ?? $featured->category->name ?? 'Sin categoría' }}
                </span>
                <span class="featured-card__chip featured-card__chip--muted">
                  {{ $featured->published_at?->format('d/m/Y') ?? 'Sin fecha' }}
                </span>
              </div>
              <h3>{{ $featured->title }}</h3>
              <p>{{ \Illuminate\Support\Str::limit($featured->excerpt ?? strip_tags($featured->content), 150) }}</p>
              <div class="featured-card__tags">
                @if($featured->leading_figure)
                  <span class="pill pill--outline">{{ $featured->leading_figure }}</span>
                @endif
                @if($featured->category)
                  <span class="pill pill--muted">{{ $featured->category->name }}</span>
                @endif
              </div>
              <div class="featured-card__actions">
                <a href="{{ route('historia.show', $featured) }}" class="btn-neo btn-compact">Ver historia</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>
    </section>
  @endif

  <section class="glass-panel section-block">
    <div class="section-heading">
      <h2>Historias disponibles</h2>
      <a href="{{ route('historia.create') }}" class="link-ghost">Agregar nueva historia</a>
    </div>
    <div class="collection-grid">
      @forelse($items as $item)
        <article class="collection-card">
          @if($item->cover_url)
            <img src="{{ $item->cover_url }}" alt="Portada de {{ $item->title }}" class="collection-cover">
          @endif
          <div class="collection-meta">
            <span>{{ $item->category->name ?? 'Sin categoría' }}</span>
            <span>por {{ $item->author->name ?? 'Desconocido' }}</span>
            <span>{{ $item->published_at?->format('d/m/Y') }}</span>
          </div>
          <div class="collection-tags">
            @if($item->era)
              <span class="pill pill--accent">{{ $item->era }}</span>
            @endif
            @if($item->leading_figure)
              <span class="pill pill--outline">{{ $item->leading_figure }}</span>
            @endif
          </div>
          <h3>{{ $item->title }}</h3>
          <p>{{ \Illuminate\Support\Str::limit($item->excerpt ?: strip_tags($item->content), 150) }}</p>
          <div class="collection-actions">
            <a href="{{ route('historia.show', $item) }}" class="btn-neo" style="padding: 0.55rem 1.35rem;">Ver</a>
            <a href="{{ route('historia.edit', $item) }}" class="btn-ghost" style="padding: 0.55rem 1.35rem;">Editar</a>
          </div>
        </article>
      @empty
        <p class="empty-state">Todavía no se cargaron historias. Crea la primera para inaugurar la colección.</p>
      @endforelse
    </div>
  </section>

  @if($items instanceof \Illuminate\Contracts\Pagination\Paginator || $items instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)
    <div class="glass-panel" style="padding: 1.4rem 1.8rem;">
      {{ $items->withQueryString()->links() }}
    </div>
  @endif
</div>
@endsection