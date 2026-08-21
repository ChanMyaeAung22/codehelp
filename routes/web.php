<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionVoteController;
use App\Http\Controllers\AnswerVoteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ReportController;


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

    //question edit
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])
    ->name('questions.edit');

    //question update
    Route::put('/questions/{question}', [QuestionController::class, 'update'])
    ->name('questions.update');

    //question delete
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])
    ->name('questions.destroy');

    //Accept Answer
    Route::post('/questions/{question}/answers/{answer}/accept',[AnswerController::class, 'accept'])
        ->name('answers.accept');

    //Filter by Tags
    Route::get('/questions/tag/{tag:slug}', [QuestionController::class, 'byTag'])
    ->name('questions.by-tag');
    
    //Edit Answer
    Route::get(
        '/questions/{question}/answers/{answer}/edit',
        [AnswerController::class, 'edit']
        )->name('answers.edit');
    
    //Update Answer
    Route::put(
        '/questions/{question}/answers/{answer}',
        [AnswerController::class, 'update']
        )->name('answers.update');

    //Delete answer
    Route::delete('/questions/{question}/answers/{answer}', [AnswerController::class, 'destroy'])
    ->name('answers.destroy');

    //Voting
    Route::post('/questions/{question}/vote', [QuestionVoteController::class, 'store'])
    ->name('questions.vote');

    Route::post('/answers/{answer}/vote', [AnswerVoteController::class, 'store'])
    ->name('answers.vote');

    //Comment
    Route::post('/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comments.store');

    //Report to Admin
    Route::post('/reports', [ReportController::class, 'store'])
    ->name('reports.store');

    Route::get('/questions/{question}/report', [ReportController::class, 'create'])
    ->name('reports.create');

    Route::get('/answers/{answer}/report', [ReportController::class, 'createAnswer'])
    ->name('reports.answer.create');

    Route::patch('/admin/reports/{report}/status', [ReportController::class, 'updateStatus'])
    ->name('admin.reports.status');

    Route::get('/comments/{comment}/report', [ReportController::class, 'createComment'])
    ->name('reports.comment.create');
});

//admin route
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])
    ->name('admin.dashboard');

    Route::get('/admin/users', [AdminController::class, 'users'])
        ->name('admin.users');

    Route::get('/admin/questions', [AdminController::class, 'questions'])
        ->name('admin.questions');

    Route::delete('/admin/questions/{question}', [AdminController::class, 'deleteQuestion'])
        ->name('admin.questions.delete');

    Route::get('/admin/answers', [AdminController::class, 'answers'])
        ->name('admin.answers');

    Route::delete('/admin/answers/{answer}', [AdminController::class, 'deleteAnswer'])
        ->name('admin.answers.delete');

    Route::get('/admin/reports', [AdminController::class, 'reports'])
        ->name('admin.reports');

    Route::delete('/admin/reports/{report}', [AdminController::class, 'deleteReport'])
        ->name('admin.reports.delete');

    Route::get('/admin/reports', [ReportController::class, 'index'])
        ->name('admin.reports');

    Route::get('/admin/comments', [AdminController::class, 'comments'])
        ->name('admin.comments');

    Route::delete('/admin/comments/{comment}', [AdminController::class, 'deleteComment'])
        ->name('admin.comments.delete');

    
});



require __DIR__.'/settings.php';
