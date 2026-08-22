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

  public function users(Request $request)
{
    $users = User::query()
        ->when($request->search, function ($query, $search) {
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
            'search' => $request->search,
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
        ->paginate(10)
        ->withQueryString();

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
        ->paginate(10)
        ->withQueryString();

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

public function suspendUser(Request $request, User $user): RedirectResponse
{
    $request->validate([
        'days' => ['required', 'integer', 'in:7,30,90,365'],
    ]);

    $user->update([
        'status' => 'suspended',
        'suspended_until' => now()->addDays($request->days),
    ]);

    return redirect()
        ->route('admin.users')
        ->with('success', 'User suspended successfully.');
}

public function unsuspendUser(User $user): RedirectResponse
{
    $user->update([
        'status' => 'active',
        'suspended_until' => null,
        'suspension_reason' => null,
    ]);

    return redirect()
        ->route('admin.users')
        ->with('success', 'User unsuspended successfully.');
}

public function banUser(User $user): RedirectResponse
{
    if ($user->is_admin) {
        return redirect()
            ->route('admin.users')
            ->with('error', 'Admin accounts cannot be banned.');
    }

    $user->update([
        'status' => 'banned',
        'suspended_until' => null,
        'suspension_reason' => null,
    ]);

    return redirect()
        ->route('admin.users')
        ->with('success', 'User banned successfully.');
}

public function unbanUser(User $user): RedirectResponse
{
    // Do not allow an admin to unban another admin
    if ($user->is_admin) {
        return redirect()
            ->route('admin.users')
            ->with('error', 'Admin accounts cannot be modified.');
    }

    $user->update([
        'status' => 'active',
        'suspended_until' => null,
        'suspension_reason' => null,
    ]);

    return redirect()
        ->route('admin.users')
        ->with('success', 'User has been unbanned successfully.');
}
}