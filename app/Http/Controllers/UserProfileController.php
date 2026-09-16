<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class UserProfileController extends Controller
{
    public function show(User $user): Response
    {
        $user->load([
            'questions' => function ($query) {
                $query->with(['tags', 'votes', 'answers'])
                    ->latest()
                    ->take(10);
            },
            'answers' => function ($query) {
                $query->with(['question', 'votes'])
                    ->latest()
                    ->take(10);
            },
        ]);

        return Inertia::render('users/Profile', [
            'profileUser' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'created_at' => $user->created_at,
                'questions'  => $user->questions,
                'answers'    => $user->answers,
                'question_count' => $user->questions()->count(),
                'answer_count'   => $user->answers()->count(),
            ],
        ]);
    }
}
