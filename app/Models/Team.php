<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    protected $fillable = ['name', 'code', 'color', 'password', 'correct_answers', 'is_winner'];

    protected $casts = [
        'is_winner' => 'boolean',
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
