<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\UserScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function ranking()
    {
        $ranking = UserScore::with('user')->orderByDesc('score')->take(20)->get();
        return view('quiz.ranking', compact('ranking'));
    }
    public function index()
    {
        $questions = Question::inRandomOrder()->limit(10)->get();
        return view('quiz.index', compact('questions'));
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_answer' => 'required|string',
        ]);

        $score = 0;
        foreach ($data['answers'] as $answer) {
            $question = Question::find($answer['question_id']);
            $isCorrect = $question->correct_answer === $answer['selected_answer'];
            UserAnswer::create([
                'user_id' => Auth::id(),
                'question_id' => $question->id,
                'selected_answer' => $answer['selected_answer'],
                'is_correct' => $isCorrect,
            ]);
            if ($isCorrect) {
                $score++;
            }
        }

        // Actualizar o crear el puntaje del usuario
        $userScore = UserScore::firstOrCreate(
            ['user_id' => Auth::id()],
            ['score' => 0, 'quizzes_taken' => 0, 'games_played' => 0, 'points' => 0]
        );
        $userScore->score += $score;
        $userScore->quizzes_taken += 1;
        $userScore->save();

        return redirect()->route('quiz.result')->with('score', $score);
    }

    public function result()
    {
        $score = session('score');
        return view('quiz.result', compact('score'));
    }
}
