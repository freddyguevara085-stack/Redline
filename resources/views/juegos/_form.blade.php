@php
    $gameModel = $game ?? null;
    $typeLabels = \App\Models\Game::TYPE_LABELS;
    $typeDescriptions = \App\Models\Game::TYPE_DESCRIPTIONS;
    $selectedType = old('type', $gameModel->type ?? 'quiz');
    $oldQuestions = old('questions', []);
@endphp

@csrf
<div class="form-grid">
  <div class="field-block field-block--full">
    <label class="field-label" for="title">Título del juego</label>
    <input id="title" class="input" name="title" value="{{ old('title',$gameModel->title ?? '') }}" placeholder="Ej: Quiz de cultura" required>
  </div>

  <div class="field-block field-block--full">
    <label class="field-label" for="description">Descripción</label>
    <textarea id="description" class="input" name="description" rows="4" placeholder="Explica la dinámica y objetivos">{{ old('description',$gameModel->description ?? '') }}</textarea>
  </div>

  <div class="field-block">
    <label class="field-label" for="points">Puntos por pregunta</label>
    <input id="points" type="number" name="points_per_question" class="input" min="1" value="{{ old('points_per_question', $gameModel->points_per_question ?? 1) }}" placeholder="Puntos" required>
  </div>

  <div class="field-block" data-game-type-wrapper>
    <label class="field-label" for="type">Tipo de juego</label>
    <select id="type" name="type" class="input" required data-game-type-selector data-help-map='@json($typeDescriptions)'>
      <option value="">Selecciona un tipo</option>
      @foreach($typeLabels as $value => $label)
        <option value="{{ $value }}" @selected($selectedType === $value)>{{ $label }}</option>
      @endforeach
    </select>
    <small class="muted" data-game-type-help>{{ $typeDescriptions[$selectedType] ?? 'Selecciona un tipo para conocer la dinámica del juego.' }}</small>
  </div>

  @if(! $gameModel)
    <div class="field-block field-block--full" data-question-builder data-existing-questions='@json($oldQuestions)' data-initial-mode="{{ $selectedType }}">
      <div class="section-heading" style="padding-left:0; padding-right:0;">
        <div>
          <span class="card-eyebrow">Contenido del juego</span>
          <h3 style="margin:0.35rem 0 0;">Preguntas y respuestas</h3>
        </div>
        <button type="button" class="btn-ghost btn-compact" data-question-add>Agregar pregunta</button>
      </div>
      <p class="muted" data-question-mode-hint>
        @if($selectedType === 'memoria')
          Define pares de cartas ingresando el enunciado y la respuesta correcta para cada una.
        @else
          Agrega preguntas tipo quiz con múltiples opciones y marca cuál es la correcta.
        @endif
      </p>

      <div class="question-builder" data-question-list>
        <div class="question-builder__empty" data-question-empty>
          <p class="muted">Aún no has agregado preguntas. Usa «Agregar pregunta» para comenzar.</p>
        </div>
      </div>

      @php
        $questionErrors = collect($errors->getMessages())
            ->filter(fn ($messages, $key) => str_starts_with($key, 'questions.'));
      @endphp
      @if($questionErrors->isNotEmpty())
        <div class="form-errors" style="margin-top: 0;">
          <ul>
            @foreach($questionErrors as $messages)
              @foreach($messages as $message)
                <li>{{ $message }}</li>
              @endforeach
            @endforeach
          </ul>
        </div>
      @endif

      <template data-question-template>
        <article class="question-card" data-question-card data-question-index="__INDEX__">
          <header class="question-card__header">
            <h4>Pregunta <span data-question-number></span></h4>
            <button type="button" class="btn-ghost btn-compact" data-question-remove>Eliminar</button>
          </header>

          <div class="field-block">
            <label class="field-label">Enunciado</label>
            <input type="text" class="input" name="questions[__INDEX__][statement]" placeholder="Escribe la pregunta" required>
          </div>

          <section class="question-card__mode" data-question-mode="quiz">
            <p class="muted">Añade opciones de respuesta y selecciona cuál es la correcta.</p>
            <div class="question-option-list" data-option-list></div>
            <div class="question-option-actions">
              <button type="button" class="btn-ghost btn-compact" data-option-add>Agregar opción</button>
            </div>
          </section>

          <section class="question-card__mode" data-question-mode="memoria" hidden>
            <label class="field-label">Respuesta correcta</label>
            <input type="text" class="input" name="questions[__INDEX__][answer]" placeholder="Texto que empareja la carta" data-memory-answer>
          </section>
        </article>
      </template>

      <template data-option-template>
        <div class="question-option" data-option-row data-option-index="__OPTION_INDEX__">
          <label class="question-option__radio" title="Marcar como correcta">
            <input type="radio" name="questions[__INDEX__][correct_option]" value="__OPTION_INDEX__">
            <span>Correcta</span>
          </label>
          <input type="text" class="input" name="questions[__INDEX__][options][__OPTION_INDEX__][text]" placeholder="Texto de la opción" required>
          <button type="button" class="btn-ghost btn-compact" data-option-remove>Quitar</button>
        </div>
      </template>

      <p class="muted question-builder__note">Puedes editar estas preguntas después desde la sección «Gestionar preguntas» del juego.</p>
    </div>
  @else
    <div class="field-block field-block--full">
      <div class="neo-card" style="padding:1.1rem 1.2rem;">
        <h4 style="margin:0 0 0.4rem;">Preguntas y respuestas</h4>
        <p class="muted" style="margin:0;">Para actualizar el contenido del juego, utiliza la sección <a href="{{ route('juegos.questions', $gameModel) }}">Gestionar preguntas</a>.</p>
      </div>
    </div>
  @endif

  <div class="form-actions">
    <button class="btn-neo" type="submit">Guardar</button>
    <a href="{{ route('juegos.index') }}" class="btn-ghost">Cancelar</a>
  </div>
</div>