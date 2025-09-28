@php
    $gameModel = $game ?? null;
@endphp

@csrf
<div class="form-grid">
  <div class="field-block">
    <label class="field-label" for="title">Título del juego</label>
    <input id="title" class="input" name="title" value="{{ old('title',$gameModel->title ?? '') }}" placeholder="Ej: Quiz de cultura" required>
  </div>

  <div class="field-block">
    <label class="field-label" for="description">Descripción</label>
    <textarea id="description" class="input" name="description" rows="4" placeholder="Explica la dinámica y objetivos">{{ old('description',$gameModel->description ?? '') }}</textarea>
  </div>

  <div class="field-block">
    <label class="field-label" for="points">Puntos por pregunta</label>
    <input id="points" type="number" name="points_per_question" class="input" min="1" value="{{ old('points_per_question', $gameModel->points_per_question ?? 1) }}" placeholder="Puntos" required>
  </div>

  <div class="field-block">
    <label class="field-label" for="type">Tipo de juego</label>
    <select id="type" name="type" class="input" required>
      @php($types = \App\Models\Game::TYPES)
      <option value="">Selecciona un tipo</option>
      @foreach($types as $type)
        <option value="{{ $type }}" @selected(old('type', $gameModel->type ?? 'trivia') === $type)>{{ ucfirst($type) }}</option>
      @endforeach
    </select>
    <small class="muted">El tipo define la mecánica: trivia, ruleta, juicio o memoria.</small>
  </div>

  <div class="form-actions">
    <button class="btn-neo" type="submit">Guardar</button>
    <a href="{{ route('juegos.index') }}" class="btn-ghost">Cancelar</a>
  </div>
</div>