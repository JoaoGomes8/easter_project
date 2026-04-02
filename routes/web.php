<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BinaryGameController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/check-team', [HomeController::class, 'checkTeam'])->name('home.check-team');
Route::post('/verify-password', [HomeController::class, 'verifyPassword'])->name('home.verify-password');
Route::post('/join', [HomeController::class, 'joinTeam'])->name('home.join');
Route::post('/logout', [HomeController::class, 'logout'])->name('home.logout');

Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::post('/quiz/submit', [QuizController::class, 'submitAnswer'])->name('quiz.submit');

Route::get('/game', [BinaryGameController::class, 'show'])->name('game.show');
Route::post('/game/submit', [BinaryGameController::class, 'submit'])->name('game.submit');
Route::post('/game/validate-binary', [BinaryGameController::class, 'validateBinaryGuess'])->name('game.validate-binary');
Route::get('/game/guessed', [BinaryGameController::class, 'getGuessedIds'])->name('game.guessed');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/progress', [DashboardController::class, 'getProgress'])->name('dashboard.progress');
