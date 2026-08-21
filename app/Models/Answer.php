<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Answer extends Model
{
    // Without $fillable, Laravel would save everything from the request.
    protected $fillable = [
        'question_id',
        'user_id',
        'content',
        'is_accepted',
    ];

    /**
     * The user who wrote this answer.
     * 
     */
    /** @return BelongsTo<User, Answer> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The question this answer belongs to.
     */
    /** @return BelongsTo<Question, Answer> */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Comments on this answer.
     */
    /** @return MorphMany<Comment, Answer> */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /** @return HasMany<AnswerVote, Answer> */
    public function votes(): HasMany
    {
        return $this->hasMany(AnswerVote::class);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
