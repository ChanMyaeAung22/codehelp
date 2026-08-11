<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Questions that use this tag.
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class); // A tag can belong to many questions.
    }
}
