@php
    $newsItem = $news ?? null;
@endphp

@csrf
<div class="form-grid">
  <div class="field-block">
    <label class="field-label" for="title">Título</label>
    <input id="title" class="input" name="title" value="{{ old('title',$newsItem->title ?? '') }}" placeholder="Titular atractivo" required>
  </div>

  <div class="field-block">
    <label class="field-label" for="body">Contenido</label>
    <textarea id="body" class="input" name="body" rows="6" placeholder="Escribe la noticia completa" required>{{ old('body',$newsItem->body ?? '') }}</textarea>
  </div>

  <div class="field-block">
    <label class="field-label" for="image">Imagen principal</label>
    <input id="image" type="file" name="cover" class="input">
    <span class="field-hint">Opcional, ideal para destacar la noticia en el listado.</span>
  </div>

  <div class="form-actions">
    <button class="btn-neo" type="submit">Guardar</button>
    <a href="{{ route('noticias.index') }}" class="btn-ghost">Cancelar</a>
  </div>
</div>