<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5'],
        ]);

        $question->answers()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('questions.show', $question);
    }

    public function accept(
         Request $request,
    Question $question,
    Answer $answer
    ): RedirectResponse {
    abort_unless($request->user()->id === $question->user_id, 403);

    abort_unless($answer->question_id === $question->id, 404);

    // If this answer is already accepted, unaccept it.
    if ($answer->is_accepted) {
        $answer->update([
            'is_accepted' => false,
        ]);

        return back();
    }

    // Otherwise, remove accepted status from all answers.
    $question->answers()->update([
        'is_accepted' => false,
    ]);

    // Accept this answer.
    $answer->update([
        'is_accepted' => true,
    ]);

    return back();
}
}