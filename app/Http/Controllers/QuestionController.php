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

    public function show(Question $question)
    {
        $question->load([
            'user',
            'answers.user',
            'answers.votes',
            'votes',
            'answers.comments.user',
            'comments.user',
        ]);

        return Inertia::render('questions/Show', [
            'question' => $question,
        ]);
    }

    public function edit(Question $question): Response
    {
        abort_unless(auth()->id() === $question->user_id, 403);

        return Inertia::render('questions/Edit', [
        'question' => $question,
        ]);
    }

    public function update(Request $request, Question $question): RedirectResponse
    {
        abort_unless(auth()->id() === $question->user_id, 403); //Only the person who owns the question can edit it.

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        $question->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('questions.show', $question)
            ->with('success', 'Question updated successfully!');
    }
    }
