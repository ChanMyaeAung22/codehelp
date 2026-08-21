<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Comment;


class ReportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reportable_type' => [
                'required',
                'string',
                'in:question,answer,comment',
            ],
            'reportable_id' => [
                'required',
                'integer',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'return_url' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $modelClass = match ($validated['reportable_type']) {
            'question' => \App\Models\Question::class,
            'answer' => \App\Models\Answer::class,
            'comment' => \App\Models\Comment::class,
        };

        $reportable = $modelClass::findOrFail($validated['reportable_id']);

        $report = new Report();

        $report->user_id = $request->user()->id;
        $report->reason = $validated['reason'];
        $report->description = $validated['description'] ?? null;
        $report->status = 'pending';

        $report->reportable()->associate($reportable);

        $alreadyReported = Report::where('user_id', $request->user()->id)
            ->where('reportable_type', $modelClass)
            ->where('reportable_id', $reportable->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyReported) {
            return back()->withErrors([
                'report' => 'You have already reported this content.',
            ]);
        }

        $report->save();

        if (!empty($validated['return_url'])) {
    return redirect($validated['return_url'])
        ->with('success', 'Report submitted successfully.');
}

return back()->with(
    'success',
    'Report submitted successfully.'
);
    }

    /**
     * Show the report form for a question.
     */
    public function create(Request $request, Question $question)
    {
    return Inertia::render('reports/Create', [
        'reportable' => $question,
        'type' => 'question',
        'return_url' => $request->headers->get('referer'),
    ]);
    }

public function createAnswer(Request $request, Answer $answer)
    {
    $answer->load(['user', 'question']);

    return Inertia::render('reports/Create', [
        'reportable' => [
            'id' => $answer->id,
            'title' => 'Answer by ' . $answer->user->name,
            'description' => $answer->content,
        ],
        'type' => 'answer',
        'return_url' => $request->query('return_url'),
    ]);
        }

public function updateStatus(Request $request, Report $report): RedirectResponse
    {
    $validated = $request->validate([
        'status' => [
            'required',
            'in:resolved,dismissed',
        ],
    ]);

    $report->update([
        'status' => $validated['status'],
    ]);

    return back()->with(
        'success',
        'Report status updated successfully.'
    );
    }

public function index(Request $request)
    {
    $status = $request->input('status');
    $type = $request->input('type');
    $search = $request->input('search');

    $reports = Report::with(['user', 'reportable'])
        ->when($status, function ($query) use ($status) {
            $query->where('status', $status);
        })
        ->when($type, function ($query) use ($type) {
            $modelClass = match ($type) {
                'question' => Question::class,
                'answer' => Answer::class,
                'comment' => Comment::class,
                default => null,
            };

            if ($modelClass) {
                $query->where('reportable_type', $modelClass);
            }
        })
        ->when($search, function ($query) use ($search) {
            $query->where(function ($query) use ($search) {

                // Search reporter name
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })

                // Search report reason
                ->orWhere('reason', 'like', "%{$search}%")

                // Search report description
                ->orWhere('description', 'like', "%{$search}%")

                // Search reported question/answer
                ->orWhereHasMorph(
                    'reportable',
                    [Question::class, Answer::class],
                    function ($reportableQuery, $type) use ($search) {

                        if ($type === Question::class) {
                            $reportableQuery->where(function ($query) use ($search) {
                                $query->where('title', 'like', "%{$search}%")
                                    ->orWhere('description', 'like', "%{$search}%");
                            });
                        }

                        if ($type === Answer::class) {
                            $reportableQuery->where(
                                'content',
                                'like',
                                "%{$search}%"
                            );
                        }
                    }
                );
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('admin/Reports', [
    'reports' => $reports->items(),

    'pagination' => [
        'current_page' => $reports->currentPage(),
        'last_page' => $reports->lastPage(),
        'per_page' => $reports->perPage(),
        'total' => $reports->total(),
        'from' => $reports->firstItem(),
        'to' => $reports->lastItem(),
        'links' => $reports->linkCollection(),
    ],

    'filters' => [
        'status' => $status,
        'type' => $type,
        'search' => $search,
    ],
    ]);
    }

    public function createComment(Request $request, Comment $comment)
{
    $comment->load(['user', 'commentable']);

    return Inertia::render('reports/Create', [
        'reportable' => [
            'id' => $comment->id,
            'title' => 'Comment by ' . $comment->user->name,
            'description' => $comment->content,
        ],
        'type' => 'comment',
        'return_url' => $request->query('return_url'),
    ]);
}

public function viewReportedContent(Report $report): RedirectResponse
{
    $reportable = $report->reportable;

    if (!$reportable) {
        abort(404);
    }

    if ($reportable instanceof Question) {
        return redirect()->route('questions.show', $reportable->id);
    }

    if ($reportable instanceof Answer) {
        return redirect()->route('questions.show', $reportable->question_id);
    }

    if ($reportable instanceof Comment) {
        $commentable = $reportable->commentable;

        if ($commentable instanceof Question) {
            return redirect()->route('questions.show', $commentable->id);
        }

        if ($commentable instanceof Answer) {
            return redirect()->route(
                'questions.show',
                $commentable->question_id
            );
        }
    }

    abort(404);
}
}