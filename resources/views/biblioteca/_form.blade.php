@php
    $libraryItem = $item ?? null;
@endphp

@csrf
<div class="form-grid">
  <div class="field-block">
    <label for="title" class="field-label">Título</label>
    <input id="title" class="input" name="title" value="{{ old('title',$libraryItem->title ?? '') }}" placeholder="Ej: Guía de estudio de biología" required>
  </div>

  <div class="field-block">
    <label for="type" class="field-label">Tipo de recurso</label>
    <select id="type" class="input" name="type" required>
      <option value="">Selecciona un tipo</option>
      <option value="pdf" @selected(old('type',$libraryItem->type??'')=='pdf')>PDF</option>
      <option value="image" @selected(old('type',$libraryItem->type??'')=='image')>Imagen</option>
      <option value="video" @selected(old('type',$libraryItem->type??'')=='video')>Video</option>
      <option value="link" @selected(old('type',$libraryItem->type??'')=='link')>Enlace</option>
    </select>
  </div>

  <div class="field-block field-block--full">
    <label for="description" class="field-label">Descripción</label>
    <textarea id="description" class="input" name="description" rows="4" placeholder="Describe el contenido y cómo se puede aprovechar">{{ old('description',$libraryItem->description ?? '') }}</textarea>
  </div>

  <div class="field-block field-block--full">
    <label for="cover" class="field-label">Imagen de portada</label>
    <input id="cover" type="file" name="cover" class="input" accept="image/png,image/jpeg,image/webp">
    <span class="field-hint">JPG/PNG/WebP · mínimo 800×600 px · máximo 3&nbsp;MB.</span>
  </div>

  @if($libraryItem && $libraryItem->cover_path)
    <div class="field-block field-block--full">
      <label class="field-label">Portada actual</label>
      <img src="{{ asset('storage/'.ltrim($libraryItem->cover_path, '/')) }}" alt="Portada actual del recurso" style="max-width: min(320px, 100%); border-radius: 14px; box-shadow: var(--shadow-sm);">
      <span class="field-hint">Se reemplazará si eliges una imagen nueva.</span>
    </div>
  @endif

  <div class="field-block">
    <label for="external_url" class="field-label">Enlace externo</label>
    <input id="external_url" type="url" class="input" name="external_url" placeholder="https://" value="{{ old('external_url',$libraryItem->external_url??'') }}">
    <span class="field-hint">Útil para enlazar recursos alojados en plataformas externas.</span>
  </div>

  <div class="field-block">
    <label for="video_url" class="field-label">Video (YouTube o Vimeo)</label>
    <input id="video_url" type="url" class="input" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="{{ old('video_url', $libraryItem->video_url ?? '') }}">
    <span class="field-hint">Para recursos de tipo video puedes pegar un enlace compatible o subir un archivo.</span>
  </div>

  <div class="field-block field-block--full">
    <label for="video_caption" class="field-label">Descripción o subtítulos del video</label>
    <textarea id="video_caption" class="input" name="video_caption" rows="3" placeholder="Describe brevemente el contenido del video para accesibilidad.">{{ old('video_caption', $libraryItem->video_caption ?? '') }}</textarea>
    <span class="field-hint">Se mostrará junto al reproductor para brindar contexto a personas con dificultades auditivas o visuales.</span>
  </div>

  <div class="field-block field-block--full">
    <label for="file" class="field-label">Archivo adjunto</label>
    <input id="file" type="file" name="file" class="input">
    <span class="field-hint">Formatos permitidos: PDF, imágenes o videos hasta 50&nbsp;MB.</span>
  </div>

  <div class="form-actions">
    <button class="btn-neo" type="submit">Guardar recurso</button>
    <a href="{{ route('biblioteca.index') }}" class="btn-ghost">Cancelar</a>
  </div>
</div>