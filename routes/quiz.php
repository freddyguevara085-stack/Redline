<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::middleware(['auth'])->group(function () {
    Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
    Route::post('/quiz/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/result', [QuizController::class, 'result'])->name('quiz.result');
    Route::get('/quiz/ranking', [QuizController::class, 'ranking'])->name('quiz.ranking');
});
