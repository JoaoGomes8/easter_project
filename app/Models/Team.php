<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'code', 'color', 'password', 'correct_answers', 'score', 'is_winner', 'phrase_game_completed', 'guessed_question_ids'];

    protected $casts = [
        'is_winner' => 'boolean',
        'phrase_game_completed' => 'boolean',
        'score' => 'integer',
        'guessed_question_ids' => 'array',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function getCorrectAnswersCount()
    {
        return $this->answers()->where('is_correct', true)->count();
    }
}
