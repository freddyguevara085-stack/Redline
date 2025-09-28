@csrf
@php
  $eraOptions = collect($eras ?? []);
  $leadingFigureOptions = collect($leadingFigures ?? []);
@endphp
<div class="form-row">
  <div class="form-group">
    <x-label for="title" :value="'Título'" />
    <x-input id="title" name="title" value="{{ old('title', $historia->title ?? '') }}" placeholder="Título" required />
  </div>

  <div class="form-group">
    <x-label for="category_id" :value="'Categoría'" />
    <select id="category_id" class="input" name="category_id">
      <option value="">Sin categoría</option>
      @foreach($cats as $c)
        <option value="{{ $c->id }}" @selected(old('category_id', $historia->category_id ?? '') == $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="form-group">
    <x-label for="era" :value="'Época o período'" />
    <x-input id="era" list="era-options" name="era" value="{{ old('era', $historia->era ?? '') }}" placeholder="Ej. Siglo XIX" />
    <datalist id="era-options">
      @foreach($eraOptions as $option)
        <option value="{{ $option }}">{{ $option }}</option>
      @endforeach
    </datalist>
  </div>

  <div class="form-group">
    <x-label for="leading_figure" :value="'Protagonista o referente'" />
    <x-input id="leading_figure" list="leading-figure-options" name="leading_figure" value="{{ old('leading_figure', $historia->leading_figure ?? '') }}" placeholder="Ej. Augusto C. Sandino" />
    <datalist id="leading-figure-options">
      @foreach($leadingFigureOptions as $option)
        <option value="{{ $option }}">{{ $option }}</option>
      @endforeach
    </datalist>
  </div>

  <div class="form-group">
    <x-label for="excerpt" :value="'Extracto'" />
    <textarea id="excerpt" class="input" name="excerpt" rows="3" placeholder="Resumen breve">{{ old('excerpt', $historia->excerpt ?? '') }}</textarea>
  </div>

  <div class="form-group">
    <x-label for="content" :value="'Contenido'" />
    <textarea id="content" class="input" name="content" rows="8" placeholder="Desarrollo completo" required>{{ old('content', $historia->content ?? '') }}</textarea>
  </div>

  <div class="form-group">
    <x-label for="cover" :value="'Imagen de portada'" />
    <input id="cover" type="file" name="cover" class="input" accept="image/png,image/jpeg,image/webp">
    <p class="form-hint">Formatos JPG/PNG/WebP, mínimo 800×600px y máximo 3&nbsp;MB.</p>
  </div>

  @if(optional($historia)->cover_path)
    <div class="form-group">
      <x-label :value="'Portada actual'" />
      <img src="{{ asset('storage/'.ltrim($historia->cover_path, '/')) }}" alt="Portada actual" style="max-width: 320px; border-radius: 12px; box-shadow: var(--shadow-sm);">
      <p class="form-hint">Se reemplazará si subes una imagen nueva.</p>
    </div>
  @endif

  <div class="form-group">
    <x-label for="video_url" :value="'Video (YouTube o Vimeo)'" />
    <x-input id="video_url" name="video_url" value="{{ old('video_url', $historia->video_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..." />
    <p class="form-hint">Pega la URL del video en YouTube o Vimeo para incrustarlo en la historia.</p>
  </div>

  <div class="form-footer">
    <a href="{{ route('historia.index') }}" class="btn-ghost" style="padding: 0.65rem 1.5rem;">Cancelar</a>
    <button class="btn-neo" style="padding: 0.65rem 1.5rem;">Guardar</button>
  </div>
</div>