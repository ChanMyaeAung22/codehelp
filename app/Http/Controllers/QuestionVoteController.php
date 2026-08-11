<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionVoteController extends Controller
{
    public function store(Request $request, Question $question): RedirectResponse
    {
    $validated = $request->validate([
        'vote_type' => ['required', 'in:up,down'],
    ]);

    $user = Auth::user();

    $existingVote = QuestionVote::where('question_id', $question->id)
        ->where('user_id', $user->id)
        ->first();

    if ($existingVote) {
        if ($existingVote->vote_type === $validated['vote_type']) {
            // Click the same vote again → remove it
            $existingVote->delete();
        } else {
            // Change upvote ↔ downvote
            $existingVote->update([
                'vote_type' => $validated['vote_type'],
            ]);
        }
    } else {
        QuestionVote::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'vote_type' => $validated['vote_type'],
        ]);
    }

    return back();
}
}