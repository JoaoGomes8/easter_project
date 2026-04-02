<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

$passwordsByQuestion = [
    1 => 'BUNNY',
    2 => 'EGG',
    3 => 'BASKET',
    4 => 'FESTIVE',
    5 => 'CHOCOLATE',
    6 => 'CANDY',
    7 => 'DUCKLING',
    8 => 'EASTER',
    9 => 'JESUS',
    10 => 'SPRING',
    11 => 'EASTER-GIFT',
];

$questions = Question::orderBy('question_number')->get();
$count = 0;

foreach ($questions as $question) {
    if (!isset($passwordsByQuestion[$question->question_number])) {
        continue;
    }

    $question->password = $passwordsByQuestion[$question->question_number];
    $question->save();
    $count++;
}

echo "✅ $count perguntas atualizadas com as novas passwords por ordem!\n";
