<?php
// Script para resetar as perguntas

require 'bootstrap/app.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Delete all questions
\App\Models\Question::truncate();

// Close

echo "Perguntas deletadas com sucesso.\n";
