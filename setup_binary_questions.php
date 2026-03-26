<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Question;

// Atualizar questões existentes com binários
$updates = [
    1 => '01000011',  // C
    2 => '01000101',  // E
    3 => '01010011',  // S
    4 => '01000001',  // A
    5 => '01000101',  // E
    6 => '00100000',  // espaço
    7 => '01000100',  // D
    8 => '01001001',  // I
    9 => '01000111',  // G
    10 => '01001001', // I
];

foreach ($updates as $id => $binary) {
    $q = Question::find($id);
    if ($q) {
        $q->binary_code = $binary;
        $q->save();
        echo "✅ Pergunta $id atualizada com binário $binary\n";
    }
}

// Adicionar 3 novas perguntas
$newQuestions = [
    [
        'question_number' => 11,
        'question' => 'Qual é a linguagem de estilo usada para formatar páginas web?',
        'correct_answer' => 'CSS',
        'password' => 'CESAE',
        'hint' => 'Folhas de Estilo em Cascata (Cascading Style Sheets)',
        'explanation' => 'CSS é usada para estilizar elementos HTML.',
        'binary_code' => '01010100', // T
    ],
    [
        'question_number' => 12,
        'question' => 'Qual é o banco de dados mais popular em aplicações web?',
        'correct_answer' => 'MySQL',
        'password' => 'CESAE',
        'hint' => 'Começa com M, frequentemente usado com PHP',
        'explanation' => 'MySQL é um dos bancos de dados mais usados em aplicações web.',
        'binary_code' => '01000001', // A
    ],
    [
        'question_number' => 13,
        'question' => 'O que significa a sigla "API" em programação?',
        'correct_answer' => 'Application Programming Interface',
        'password' => 'CESAE',
        'hint' => 'Interface de Programação de Aplicação',
        'explanation' => 'API é um conjunto de protocolos para construir e integrar software.',
        'binary_code' => '01001100', // L
    ],
];

foreach ($newQuestions as $q) {
    Question::create($q);
    echo "✅ Pergunta " . $q['question_number'] . " criada com binário " . $q['binary_code'] . "\n";
}

echo "\n✅ 13 perguntas configuradas! Binários mapeados para 'CESAE DIGITAL'\n";
