<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

    public function edit(
        Question $question,
        Answer $answer
    ): Response {
        abort_unless($answer->question_id === $question->id, 404);

        abort_unless(auth()->id() === $answer->user_id, 403);

        return Inertia::render('answers/Edit', [
            'question' => $question,
            'answer' => $answer,
        ]);
    }

    public function update(
        Request $request,
        Question $question,
        Answer $answer
    ): RedirectResponse {
        abort_unless($answer->question_id === $question->id, 404);

        abort_unless(auth()->id() === $answer->user_id, 403);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:5'],
        ]);

        $answer->update([
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('questions.show', $question)
            ->with('success', 'Answer updated successfully!');
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