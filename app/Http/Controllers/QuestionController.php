<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionController extends Controller
{
    public function create()
    {
        return Inertia::render('questions/Create');
    }

    public function store(Request $request)
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

    public function index()
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
            'comments.user',
            'tags',
            'votes',
        ]);

        return Inertia::render('questions/Show', [
            'question' => $question,
        ]);
    }

}