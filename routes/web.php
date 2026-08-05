<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/questions', [QuestionController::class, 'index'])
        ->name('questions.index');

    Route::get('/questions/create', [QuestionController::class, 'create'])
        ->name('questions.create');

    Route::post('/questions', [QuestionController::class, 'store'])
        ->name('questions.store');

    Route::get('/questions/{question}', [QuestionController::class, 'show'])
        ->name('questions.show');

});

require __DIR__.'/settings.php';
