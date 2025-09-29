<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Question;
use App\Models\Option;
use App\Models\GameAttempt;
use App\Models\UserScore;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show', 'play', 'submit']);

        $this->authorizeResource(Game::class, 'juego', [
            'except' => ['index', 'show', 'play', 'submit'],
        ]);
    }

    public function index()
    {
        $games = Game::withCount(['questions', 'attempts as plays_count'])
            ->latest()
            ->paginate(12);
        return view('juegos.index', compact('games'));
    }

    public function create()
    {
        return view('juegos.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'=>'required|max:160',
            'description'=>'nullable|max:1000',
            'points_per_question'=>'required|integer|min:1',
            'type' => 'required|in:'.implode(',', \App\Models\Game::TYPES),
        ]);

        [$normalizedQuestions, $questionErrors] = $this->normalizeQuestionPayload(
            $r->input('questions', []),
            $data['type']
        );

        if (! empty($questionErrors)) {
            return back()->withErrors($questionErrors)->withInput();
        }

        $data['user_id'] = auth()->id();
        $game = Game::create($data);

        foreach ($normalizedQuestions as $questionData) {
            $question = $game->questions()->create([
                'statement' => $questionData['statement'],
            ]);

            foreach ($questionData['options'] as $optionData) {
                $question->options()->create([
                    'text' => $optionData['text'],
                    'is_correct' => $optionData['is_correct'],
                ]);
            }
        }

        return redirect()->route('juegos.show',$game)->with('ok','Juego creado');
    }

    public function show(Game $juego)
    {
        $juego->loadMissing(['questions.options', 'author']);
        $juego->loadCount(['attempts as plays_count']);
        return view('juegos.show',['game'=>$juego]);
    }

    public function edit(Game $juego)
    {
        return view('juegos.edit',['game'=>$juego]);
    }

    public function update(Request $r, Game $juego)
    {
        $data = $r->validate([
            'title'=>'required|max:160',
            'description'=>'nullable|max:1000',
            'points_per_question'=>'required|integer|min:1',
            'type' => 'required|in:'.implode(',', \App\Models\Game::TYPES),
        ]);
        $juego->update($data);
        return back()->with('ok','Juego actualizado');
    }

    public function destroy(Game $juego)
    {
        $juego->delete();
        return redirect()->route('juegos.index')->with('ok','Juego eliminado');
    }

    // -----------------------------
    // CRUD de Preguntas y Opciones
    // -----------------------------
    public function questions(Game $game)
    {
        $this->authorize('manageQuestions', $game);
        $game->load('questions.options');
        return view('juegos.questions', compact('game'));
    }

    public function questionCreate(Game $game)
    {
        $this->authorize('manageQuestions', $game);
        return view('juegos.question_create', compact('game'));
    }

    public function questionStore(Request $r, Game $game)
    {
        $this->authorize('manageQuestions', $game);
        $data = $r->validate(['statement'=>'required|max:500']);
        $question = $game->questions()->create($data);
        return redirect()->route('juegos.questions', $game)->with('ok','Pregunta creada');
    }

    public function questionEdit(Game $game, Question $question)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        return view('juegos.question_edit', compact('game','question'));
    }

    public function questionUpdate(Request $r, Game $game, Question $question)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $data = $r->validate(['statement'=>'required|max:500']);
        $question->update($data);
        return back()->with('ok','Pregunta actualizada');
    }

    public function questionDestroy(Game $game, Question $question)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $question->delete();
        return back()->with('ok','Pregunta eliminada');
    }

    // Opciones
    public function optionCreate(Game $game, Question $question)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        return view('juegos.option_create', compact('game','question'));
    }

    public function optionStore(Request $r, Game $game, Question $question)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $data = $r->validate([
            'text'=>'required|max:255',
            'is_correct'=>'nullable|boolean'
        ]);
        $data['is_correct'] = $r->has('is_correct');
        $question->options()->create($data);
        return redirect()->route('juegos.questions', $game)->with('ok','Opción agregada');
    }

    public function optionEdit(Game $game, Question $question, Option $option)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $this->ensureOptionBelongsToQuestion($question, $option);
        return view('juegos.option_edit', compact('game','question','option'));
    }

    public function optionUpdate(Request $r, Game $game, Question $question, Option $option)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $this->ensureOptionBelongsToQuestion($question, $option);
        $data = $r->validate([
            'text'=>'required|max:255',
            'is_correct'=>'nullable|boolean'
        ]);
        $data['is_correct'] = $r->has('is_correct');
        $option->update($data);
        return back()->with('ok','Opción actualizada');
    }

    public function optionDestroy(Game $game, Question $question, Option $option)
    {
        $this->authorize('manageQuestions', $game);
        $this->ensureQuestionBelongsToGame($game, $question);
        $this->ensureOptionBelongsToQuestion($question, $option);
        $option->delete();
        return back()->with('ok','Opción eliminada');
    }

    // ----------------------------
    // Jugar y Corrección
    // ----------------------------
    public function play(Game $game)
    {
        $game->load(['questions.options']);

        $questions = $game->questions;

        if ($game->type === 'memoria') {
            $questions = $questions
                ->filter(fn ($question) => $question->options->firstWhere('is_correct', true))
                ->values();
        }

        if ($questions->isEmpty()) {
            return redirect()
                ->route('juegos.show', $game)
                ->withErrors(['play' => 'Este juego aún no tiene preguntas disponibles. Añade opciones correctas para activar el modo seleccionado.']);
        }

        return view('juegos.play', [
            'game' => $game,
            'questionList' => $questions,
        ]);
    }

    public function submit(Request $r, Game $game)
    {
        $game->load(['questions.options']);

        $questions = $game->questions;

        if ($game->type === 'memoria') {
            $questions = $questions
                ->filter(fn ($question) => $question->options->firstWhere('is_correct', true))
                ->values();
        }

        if ($questions->isEmpty()) {
            return redirect()
                ->route('juegos.show', $game)
                ->withErrors(['play' => 'No puedes enviar respuestas porque este juego no tiene preguntas disponibles.']);
        }

        $cooldownKey = "games.cooldown.{$game->id}";
        $now = now();
        $availableAt = $r->session()->get($cooldownKey);

        if ($availableAt && $now->lessThan($availableAt)) {
            $seconds = $now->diffInSeconds($availableAt);
            return back()->withErrors([
                'cooldown' => "Debes esperar {$seconds} segundos para volver a enviar tus respuestas.",
            ])->withInput();
        }

        $rules = [];
        foreach ($questions as $question) {
            $rules["q{$question->id}"] = ['required', 'integer', 'exists:options,id'];
        }

        $validated = $r->validate($rules);

        $total = $questions->count();
        $correct = 0;

        foreach ($questions as $q) {
            $selectedId = (int) $validated["q{$q->id}"];
            $option = $q->options->firstWhere('id', $selectedId);

            if (! $option) {
                return back()->withErrors([
                    "q{$q->id}" => 'Selecciona una opción válida para esta pregunta.',
                ])->withInput();
            }

            if ($option->is_correct) {
                $correct++;
            }
        }

        $r->session()->put($cooldownKey, $now->copy()->addSeconds(30));

        $score = $correct * $game->points_per_question;

        $attempt = GameAttempt::create([
            'game_id' => $game->id,
            'user_id' => optional($r->user())->id,
            'score' => $score,
            'correct_answers' => $correct,
            'total_questions' => $total,
            'played_at' => $now,
        ]);

        if ($attempt->user_id) {
            $userScore = UserScore::firstOrNew(['user_id' => $attempt->user_id]);

            if (! $userScore->exists) {
                $userScore->fill([
                    'points' => 0,
                    'score' => 0,
                    'quizzes_taken' => 0,
                    'games_played' => 0,
                ]);
            }

            $userScore->game_id = $game->id;
            $userScore->source = $game->type;
            $userScore->games_played = ($userScore->games_played ?? 0) + 1;
            $userScore->score = ($userScore->score ?? 0) + $score;
            $userScore->points = ($userScore->points ?? 0) + $score;

            if ($game->type === 'quiz') {
                $userScore->quizzes_taken = ($userScore->quizzes_taken ?? 0) + 1;
            }

            $userScore->save();
        }

        return view('juegos.result', compact('game','score','total','correct'));
    }

    protected function normalizeQuestionPayload(array $rawQuestions, string $type): array
    {
        $normalized = [];
        $errors = [];

        foreach ($rawQuestions as $index => $rawQuestion) {
            $statement = trim($rawQuestion['statement'] ?? '');
            $identifier = $index + 1;

            $hasContent = $statement !== ''
                || ! empty($rawQuestion['options'] ?? [])
                || isset($rawQuestion['answer']);

            if (! $hasContent) {
                continue;
            }

            if ($statement === '') {
                $errors["questions.{$index}.statement"] = "Ingresa el enunciado de la pregunta #{$identifier}.";
                continue;
            }

            if ($type === 'quiz') {
                $rawOptions = $rawQuestion['options'] ?? [];
                $preparedOptions = [];

                foreach ($rawOptions as $optionIndex => $optionData) {
                    $text = trim($optionData['text'] ?? '');
                    if ($text === '') {
                        continue;
                    }

                    $preparedOptions[] = [
                        'text' => $text,
                        'key' => (string) $optionIndex,
                    ];
                }

                if (count($preparedOptions) < 2) {
                    $errors["questions.{$index}.options"] = "Agrega al menos dos opciones para la pregunta #{$identifier}.";
                    continue;
                }

                $correctKey = isset($rawQuestion['correct_option'])
                    ? (string) $rawQuestion['correct_option']
                    : null;

                $hasCorrect = false;
                foreach ($preparedOptions as &$option) {
                    $option['is_correct'] = $correctKey !== null && $option['key'] === $correctKey;
                    if ($option['is_correct']) {
                        $hasCorrect = true;
                    }
                }
                unset($option);

                if (! $hasCorrect) {
                    $errors["questions.{$index}.correct_option"] = "Selecciona cuál opción es la correcta en la pregunta #{$identifier}.";
                    continue;
                }

                $normalized[] = [
                    'statement' => $statement,
                    'options' => collect($preparedOptions)
                        ->map(fn ($option) => [
                            'text' => $option['text'],
                            'is_correct' => $option['is_correct'],
                        ])->all(),
                ];
            } else {
                $answer = trim($rawQuestion['answer'] ?? '');

                if ($answer === '') {
                    $errors["questions.{$index}.answer"] = "Escribe la respuesta correcta para la carta #{$identifier}.";
                    continue;
                }

                $normalized[] = [
                    'statement' => $statement,
                    'options' => [[
                        'text' => $answer,
                        'is_correct' => true,
                    ]],
                ];
            }
        }

        return [$normalized, $errors];
    }

    protected function ensureQuestionBelongsToGame(Game $game, Question $question): void
    {
        if ($question->game_id !== $game->id) {
            abort(404);
        }
    }

    protected function ensureOptionBelongsToQuestion(Question $question, Option $option): void
    {
        if ($option->question_id !== $question->id) {
            abort(404);
        }
    }
}