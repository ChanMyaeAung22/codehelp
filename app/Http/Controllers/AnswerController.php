<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function store(Request $request, Question $question)
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
}
