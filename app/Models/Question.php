<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['question_number', 'question', 'correct_answer', 'password', 'hint', 'explanation', 'binary_code', 'question_type', 'options'];

    protected $casts = [
        'options' => 'json',
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
