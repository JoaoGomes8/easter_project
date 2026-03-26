<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\Answer;
use Illuminate\Console\Command;

class ResetQuestions extends Command
{
    protected $signature = 'questions:reset';
    protected $description = 'Reset all questions from database';

    public function handle()
    {
        Answer::query()->delete();
        Question::query()->delete();
        $this->info('Perguntas e respostas deletadas com sucesso.');
    }
}
