<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\AnswerVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerVoteController extends Controller
{
    public function store(Request $request, Answer $answer): RedirectResponse
    {
        $validated = $request->validate([
            'vote_type' => ['required', 'in:up,down'],
        ]);

        $user = Auth::user();

        $existingVote = AnswerVote::where('answer_id', $answer->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === $validated['vote_type']) {
                $existingVote->delete();
            } else {
                $existingVote->update([
                    'vote_type' => $validated['vote_type'],
                ]);
            }
        } else {
            AnswerVote::create([
                'answer_id' => $answer->id,
                'user_id' => $user->id,
                'vote_type' => $validated['vote_type'],
            ]);
        }

        return back();
    }
}