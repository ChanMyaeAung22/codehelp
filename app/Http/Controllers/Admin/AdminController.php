<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'users' => User::count(),
                'questions' => Question::count(),
                'answers' => Answer::count(),
                'comments' => Comment::count(),
            ],
        ]);
    }
}