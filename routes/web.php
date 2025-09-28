<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
Route::middleware(['auth'])->get('/perfil', [ProfileController::class, 'show'])->name('profile.show');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


use App\Http\Controllers\GameController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LibraryItemController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\EventController;

Route::get('historia/{historia}/cover', [HistoryController::class, 'cover'])->name('historia.cover');
Route::resource('historia', HistoryController::class)->parameters(['historia' => 'historia']);
Route::post('historia/{historia}/comentarios', [HistoryController::class, 'addComment'])
    ->middleware(['auth','throttle:5,1'])
    ->name('historia.addComment');
Route::resource('biblioteca', LibraryItemController::class);
Route::resource('juegos', GameController::class);
Route::prefix('juegos/{game}')->group(function () {
    Route::get('play', [GameController::class, 'play'])->name('juegos.play');
    Route::post('play', [GameController::class, 'submit'])->name('juegos.submit');

    Route::middleware('auth')->group(function () {
        Route::get('preguntas', [GameController::class, 'questions'])->name('juegos.questions');
        Route::get('preguntas/crear', [GameController::class, 'questionCreate'])->name('juegos.questionCreate');
        Route::post('preguntas', [GameController::class, 'questionStore'])->name('juegos.questionStore');
        Route::get('preguntas/{question}/editar', [GameController::class, 'questionEdit'])->name('juegos.questionEdit');
        Route::put('preguntas/{question}', [GameController::class, 'questionUpdate'])->name('juegos.questionUpdate');
        Route::delete('preguntas/{question}', [GameController::class, 'questionDestroy'])->name('juegos.questionDestroy');

        Route::get('preguntas/{question}/opciones/crear', [GameController::class, 'optionCreate'])->name('juegos.optionCreate');
        Route::post('preguntas/{question}/opciones', [GameController::class, 'optionStore'])->name('juegos.optionStore');
        Route::get('preguntas/{question}/opciones/{option}/editar', [GameController::class, 'optionEdit'])->name('juegos.optionEdit');
        Route::put('preguntas/{question}/opciones/{option}', [GameController::class, 'optionUpdate'])->name('juegos.optionUpdate');
        Route::delete('preguntas/{question}/opciones/{option}', [GameController::class, 'optionDestroy'])->name('juegos.optionDestroy');
    });
});
Route::resource('noticias', NewsController::class);
Route::resource('calendario', EventController::class);
Route::get('ranking', [RankingController::class, 'index'])->name('ranking.index');

require __DIR__.'/auth.php';
require __DIR__.'/quiz.php';
