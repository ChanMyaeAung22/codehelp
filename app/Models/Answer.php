<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\AnswerVote;

class Answer extends Model
{
    //Without $fillable, Laravel would save everything from the request.
    protected $fillable = [
        'question_id',
        'user_id',
        'content',
        'is_accepted',
    ];

    /**
     * The user who wrote this answer.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The question this answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Comments on this answer.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(AnswerVote::class);
    }
}