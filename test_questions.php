<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Test loading questions
$repository = new \App\Services\DatabaseQuizRepository();
$questions = $repository->getAllQuestions();

echo "Total de perguntas carregadas: " . count($questions) . "\n";
foreach ($questions as $q) {
    echo "Pergunta " . $q['question_number'] . ": " . substr($q['question'], 0, 50) . "...\n";
    echo "  Password: " . $q['password'] . "\n";
    if (isset($q['options'])) {
        echo "  Opções: " . json_encode($q['options']) . "\n";
    }
}
