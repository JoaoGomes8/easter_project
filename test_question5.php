<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$repo = new \App\Services\DatabaseQuizRepository();
$questions = $repo->getAllQuestions();

// Encontrar a pergunta 5 por question_number
$question5 = null;
foreach ($questions as $q) {
    if ($q['question_number'] == 5) {
        $question5 = $q;
        break;
    }
}

if ($question5) {
    echo "Pergunta 5:\n";
    echo "Texto: " . $question5['question'] . "\n";
    echo "Resposta correta: " . $question5['correct_answer'] . "\n";
    echo "Password: " . $question5['password'] . "\n";
    echo "Dica: " . $question5['hint'] . "\n";
    echo "Explicação: " . $question5['explanation'] . "\n";
} else {
    echo "Pergunta 5 não encontrada\n";
}
