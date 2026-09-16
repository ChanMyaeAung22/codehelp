<?php

namespace App\Http\Controllers;

use App\Models\Question;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            'tags' => ['nullable', 'array'],
            'tags.*' => ['required', 'string', 'max:50'],
        ]);

        $question = Question::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'views' => 0,
            'is_solved' => false,
        ]);

        if (!empty($validated['tags'])) {
        $tagIds = collect($validated['tags'])->map(function ($tagName) {
        $tag = Tag::firstOrCreate(
            ['name' => $tagName],
            ['slug' => Str::slug($tagName)]
            );

            return $tag->id;
            });

            $question->tags()->sync($tagIds);
        }

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question posted successfully!');
    }

    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'latest'); // 'latest' | 'popular'

        $questions = Question::with([
            'user',
            'answers',
            'tags',
            'votes',
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('tags', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            });

        $this->applySort($questions, $sort);

        $questions = $questions->paginate(10)->withQueryString();

        return Inertia::render('questions/Index', [
            'questions' => $questions,
            'search'    => $search,
            'sort'      => $sort,
        ]);
    }

    public function show(Question $question)
    {
        $question->load([
            'user',
            'answers.user',
            'answers.votes',
            'votes',
            'tags',
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

    public function byTag(Tag $tag, Request $request): Response
    {
        $sort = $request->input('sort', 'latest');

        $questions = $tag->questions()
            ->with([
                'user',
                'answers',
                'tags',
                'votes',
            ]);

        $this->applySort($questions, $sort);

        $questions = $questions->paginate(10)->withQueryString();

        return Inertia::render('questions/Index', [
            'questions'  => $questions,
            'selectedTag' => $tag,
            'sort'       => $sort,
        ]);
    }

    public function destroy(Question $question): RedirectResponse
    {
        abort_unless(auth()->id() === $question->user_id, 403);

        $question->delete();

        return redirect()
            ->route('questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Apply sorting to the given query.
     *
     * 'latest'  → newest questions first (default, existing behaviour).
     * 'popular' → ordered by (upvotes − downvotes) descending, computed
     *             on-the-fly via two SQL sub-selects — no extra DB column.
     */
    private function applySort(
        \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query,
        string $sort
    ): void
    {
        if ($sort === 'popular') {
            // Add a computed column: popularity_score = upvotes - downvotes
            $query->select('questions.*')
                ->selectSub(
                    DB::table('question_votes')
                        ->selectRaw('COUNT(*)')
                        ->whereColumn('question_id', 'questions.id')
                        ->where('vote_type', 'up'),
                    'upvote_count'
                )
                ->selectSub(
                    DB::table('question_votes')
                        ->selectRaw('COUNT(*)')
                        ->whereColumn('question_id', 'questions.id')
                        ->where('vote_type', 'down'),
                    'downvote_count'
                )
                ->orderByRaw('(upvote_count - downvote_count) DESC')
                ->orderByDesc('created_at'); // tie-break: newest first
        } else {
            $query->latest(); // existing default behaviour
        }
    }
}
