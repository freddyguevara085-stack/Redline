{{-- resources/views/juegos/play.blade.php --}}
@extends('layouts.app')
@section('content')
<div class="section-block">
  @if ($errors->any())
    <x-auth-validation-errors :errors="$errors" class="alert-error" />
  @endif

  <div class="page-hero">
    <div>
      <h1>{{ $game->title }}</h1>
      @if($game->description)
        <p>{{ $game->description }}</p>
      @endif
    </div>
    <div class="hero-actions">
      <a href="{{ route('juegos.index') }}" class="btn-ghost">Volver a juegos</a>
    </div>
  </div>

  <section class="glass-panel">
    <div class="section-heading">
      <h2>{{ $game->type === 'memoria' ? 'Juego de memoria' : 'Preguntas' }}</h2>
      <span class="tag">{{ $questionList->count() }} items</span>
    </div>

    @if($game->type === 'memoria')
      @php
        $memoryPairs = collect($questionList)->map(function ($question) {
            $correct = $question->options->firstWhere('is_correct', true);
            if (! $correct) {
                return null;
            }

            return [
                'pair' => 'q' . $question->id,
                'question_id' => $question->id,
                'prompt' => $question->statement,
                'answer' => $correct->text,
                'option_id' => $correct->id,
            ];
        })->filter()->values();

        $memoryCards = $memoryPairs->flatMap(function ($pair) {
            return [
                [
                    'pair' => $pair['pair'],
                    'question_id' => $pair['question_id'],
                    'option_id' => null,
                    'type' => 'prompt',
                    'content' => $pair['prompt'],
                ],
                [
                    'pair' => $pair['pair'],
                    'question_id' => $pair['question_id'],
                    'option_id' => $pair['option_id'],
                    'type' => 'answer',
                    'content' => $pair['answer'],
                ],
            ];
        })->shuffle();
      @endphp

      <form method="POST"
            action="{{ route('juegos.submit', $game) }}"
            class="memory-shell"
            data-memory-game
            data-memory-pairs="{{ $memoryPairs->count() }}">
        @csrf

        @foreach($questionList as $question)
          <input type="hidden" name="q{{ $question->id }}" data-memory-input="{{ $question->id }}" value="">
        @endforeach

        <div class="memory-status">
          <span class="card-eyebrow">Avance</span>
          <span data-memory-status>0 / {{ $memoryPairs->count() }}</span>
        </div>

        <p class="muted" data-memory-hint>
          Observa las tarjetas cuando inicies la ronda y encuentra cada pareja de pregunta y respuesta.
        </p>

        <div class="memory-feedback flash-feedback" data-memory-feedback>
          Presiona «Iniciar ronda» para barajar y mostrar las tarjetas durante unos segundos.
        </div>

        <div class="memory-grid memory-grid--interactive" data-memory-board>
          @foreach($memoryCards as $card)
            <button type="button"
                    class="memory-card"
                    data-memory-card
                    data-card-type="{{ $card['type'] }}"
                    data-pair="{{ $card['pair'] }}"
                    data-question-id="{{ $card['question_id'] }}"
                    @if($card['option_id']) data-option-id="{{ $card['option_id'] }}" @endif
            >
              <span class="memory-front">?</span>
              <span class="memory-back">{{ $card['content'] }}</span>
            </button>
          @endforeach
        </div>

        <div class="form-actions" style="justify-content: space-between;">
          <div class="memory-controls">
            <button type="button" class="btn-ghost btn-compact" data-memory-action="restart" hidden>Reiniciar</button>
            <button type="button" class="btn-neo btn-compact" data-memory-action="start">Iniciar ronda</button>
          </div>
          <button class="btn-neo" data-memory-submit disabled>Registrar respuestas</button>
        </div>
        <noscript><p class="muted">Este modo de juego requiere JavaScript habilitado.</p></noscript>
      </form>
    @else
      <form method="POST"
            action="{{ route('juegos.submit', $game) }}"
            class="quiz-list"
            data-quiz-form>
        @csrf

        <div class="quiz-card" style="gap: 0.6rem;">
          <div class="flash-status">
            <span class="card-eyebrow">Progreso</span>
            <span data-quiz-progress>0 / {{ $questionList->count() }}</span>
          </div>
          <p class="muted">Selecciona la respuesta correcta en cada bloque antes de enviar.</p>
        </div>

        @foreach($questionList as $idx => $q)
          @php($options = $q->options->shuffle())
          <article class="quiz-card" data-quiz-question>
            <header>
              <h3>#{{ $idx + 1 }} — {{ $q->statement }}</h3>
            </header>
            <div class="quiz-answers">
              @foreach($options as $o)
                <label class="form-check" data-quiz-option>
                  <input type="radio" name="q{{ $q->id }}" value="{{ $o->id }}" required>
                  <span>{{ $o->text }}</span>
                </label>
              @endforeach
            </div>
          </article>
        @endforeach

        <div class="form-actions">
          <button class="btn-neo">Enviar respuestas</button>
        </div>
      </form>
    @endif
  </section>
</div>
@endsection