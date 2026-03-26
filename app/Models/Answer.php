<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = ['team_id', 'question_id', 'user_answer', 'is_correct'];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
