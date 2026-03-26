<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

$questions = Question::all();
$count = 0;

foreach ($questions as $question) {
    $question->password = 'CESAE';
    $question->save();
    $count++;
}

echo "✅ $count perguntas atualizadas com password 'CESAE'!\n";
