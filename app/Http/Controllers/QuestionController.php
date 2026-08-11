<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class QuestionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('questions/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        Question::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'views' => 0,
            'is_solved' => false,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Question posted successfully!');
    }

    public function index(): Response
    {
        $questions = Question::with([
            'user',
            'answers',
            'tags',
            'votes',
        ])
            ->latest()
            ->get();

        return Inertia::render('questions/Index', [
            'questions' => $questions,
        ]);
    }

    public function show(Question $question): Response
    {
        $question->load([
            'user',
            'answers.user',
            'comments.user',
            'tags',
            'votes',
        ]);

        return Inertia::render('questions/Show', [
            'question' => $question,
        ]);
    }
}
