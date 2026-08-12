<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionVoteController;
use App\Http\Controllers\AnswerVoteController;
use App\Http\Controllers\CommentController;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    //Questions
    Route::get('/questions', [QuestionController::class, 'index'])
        ->name('questions.index');

    Route::get('/questions/create', [QuestionController::class, 'create'])
        ->name('questions.create');

    Route::post('/questions', [QuestionController::class, 'store'])
        ->name('questions.store');

    Route::get('/questions/{question}', [QuestionController::class, 'show'])
        ->name('questions.show');

    Route::post('/questions/{question}/answers', [AnswerController::class, 'store'])
        ->middleware('auth')
        ->name('answers.store');

    //Accept Answer
    Route::post('/questions/{question}/answers/{answer}/accept',[AnswerController::class, 'accept'])
        ->name('answers.accept');

    //Voting
    Route::post('/questions/{question}/vote', [QuestionVoteController::class, 'store'])
    ->name('questions.vote');

    Route::post('/answers/{answer}/vote', [AnswerVoteController::class, 'store'])
    ->name('answers.vote');

    //Comment
    Route::post('/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');
});



require __DIR__.'/settings.php';
