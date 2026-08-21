<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'reason',
        'description',
        'status',
    ];

    /**
     * The user who submitted the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The model being reported.
     *
     * Can be a Question, Answer, or Comment.
     */
    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}