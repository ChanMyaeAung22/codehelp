<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Comment;
use App\Models\Question;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Report;
use Illuminate\Http\Request;

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

                'reports' => Report::count(),
                'pendingReports' => Report::where('status', 'pending')->count(),
                'resolvedReports' => Report::where('status', 'resolved')->count(),
                'dismissedReports' => Report::where('status', 'dismissed')->count(),
            ],
        ]);
    }

    public function users(Request $request): Response
{
    $search = $request->input('search');

    $users = User::query()
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('admin/Users', [
        'users' => $users,
        'filters' => [
            'search' => $search,
        ],
    ]);
}

    public function questions(Request $request): Response
{
    $questions = Question::with([
        'user',
        'answers',
        'votes',
    ])
        ->latest()
        ->get();

    return Inertia::render('admin/Questions', [
        'questions' => $questions,
        'highlightedQuestionId' => $request->query('highlight'),
    ]);
}

    public function deleteQuestion(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()
            ->route('admin.questions')
            ->with('success', 'Question deleted successfully.');
    }

    public function answers(Request $request)
{
    $answers = Answer::with(['user', 'question'])
        ->latest()
        ->get();

    return Inertia::render('admin/Answers', [
        'answers' => $answers,
        'highlightedAnswerId' => $request->query('highlight'),
    ]);
}

    public function deleteAnswer(Answer $answer): RedirectResponse
    {
        $answer->delete();

        return redirect()
            ->route('admin.answers')
            ->with('success', 'Answer deleted successfully.');
    }

    public function reports()
    {
        $reports = Report::with([
        'user',
        'reportable',
    ])
        ->latest()
        ->get();

    return Inertia::render('admin/Reports', [
        'reports' => $reports,
    ]);
}

    public function deleteReport(\App\Models\Report $report): RedirectResponse
    {
        $report->delete();

        return redirect()
            ->route('admin.reports')
            ->with('success', 'Report deleted successfully.');
    }

public function comments(Request $request)
{
    $comments = Comment::with([
        'user',
        'commentable',
    ])
    ->latest()
    ->paginate(10);

    return Inertia::render('admin/Comments', [
        'comments' => $comments,
        'highlightedCommentId' => $request->query('highlight'),
    ]);
}

public function deleteComment(Comment $comment): RedirectResponse
{
    $comment->delete();

    return redirect()
        ->route('admin.comments')
        ->with('success', 'Comment deleted successfully.');
}
}