@php
  $calendarModel = $calendario ?? null;
  $startAt = optional($calendarModel)->start_at;
  $endAt = optional($calendarModel)->end_at;
@endphp

@csrf
<div class="form-grid">
  <div class="field-block field-block--full">
    <label class="field-label" for="title">Título del evento</label>
    <input id="title" class="input" name="title" value="{{ old('title',$calendario->title ?? '') }}" placeholder="Ej: Lanzamiento de torneo" required>
  </div>

  <div class="field-block field-block--full">
    <label class="field-label" for="description">Descripción</label>
    <textarea id="description" class="input" name="description" rows="4" placeholder="Comparte detalles y contexto del evento">{{ old('description',$calendario->description ?? '') }}</textarea>
    <span class="field-hint">Puedes incluir agenda, invitados o requisitos especiales.</span>
  </div>

  <div class="field-block">
    <label class="field-label" for="start_at">Fecha y hora de inicio</label>
    <input id="start_at" type="datetime-local" class="input" name="start_at" value="{{ old('start_at', optional($startAt)->format('Y-m-d\TH:i')) }}" required>
  </div>

  <div class="field-block">
    <label class="field-label" for="end_at">Fecha y hora de cierre</label>
    <input id="end_at" type="datetime-local" class="input" name="end_at" value="{{ old('end_at', optional($endAt)->format('Y-m-d\TH:i')) }}">
    <span class="field-hint">Opcional. Útil para eventos que abarcan varias horas o días.</span>
  </div>

  <div class="field-block field-block--full">
    <label class="field-label" for="location">Ubicación</label>
    <input id="location" class="input" name="location" value="{{ old('location',$calendario->location ?? '') }}" placeholder="Sala principal o enlace de streaming">
  </div>

  <div class="form-actions">
    <button class="btn-neo" type="submit">Guardar evento</button>
    <a href="{{ route('calendario.index') }}" class="btn-ghost">Cancelar</a>
  </div>
</div>