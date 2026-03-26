<?php

use App\Models\Question;

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$questions = [
    ['question_number' => 1, 'question' => 'Em qual linguagem foi originalmente desenvolvido o Laravel?', 'correct_answer' => 'PHP', 'password' => 'laravel', 'hint' => 'Começa com P... é uma linguagem de servidor web muito popular', 'explanation' => 'Laravel é um framework de programação web escrito em PHP.'],
    ['question_number' => 2, 'question' => 'Qual é o símbolo usado para representar a Páscoa?', 'correct_answer' => 'ovo', 'password' => 'easter', 'hint' => 'É um item redondo que coelhos entregam na Páscoa', 'explanation' => 'O ovo é o símbolo principal da Páscoa.'],
    ['question_number' => 3, 'question' => 'Qual framework JavaScript é frequentemente usado para UI?', 'correct_answer' => 'React', 'password' => 'react', 'hint' => 'Desenvolvido pelo Facebook, começa com R', 'explanation' => 'React é uma biblioteca JavaScript para construir interfaces.'],
    ['question_number' => 4, 'question' => 'Qual festival cristão comemora a ressurreição de Jesus?', 'correct_answer' => 'Páscoa', 'password' => 'jesus', 'hint' => 'A resposta está na pergunta!', 'explanation' => 'A Páscoa é o festival cristão que celebra a ressurreição de Jesus.'],
    ['question_number' => 5, 'question' => 'Em programação, o que significa HTML?', 'correct_answer' => 'HyperText Markup Language', 'password' => 'html', 'hint' => 'É a linguagem de marcação usada para criar páginas web', 'explanation' => 'HTML é o padrão para criar documentos web.'],
    ['question_number' => 6, 'question' => 'Qual é o animal tradicional associado à Páscoa?', 'correct_answer' => 'coelho', 'password' => 'bunny', 'hint' => 'É um animal fofo com longas orelhas', 'explanation' => 'O coelho de Páscoa é uma tradição moderna.'],
    ['question_number' => 7, 'question' => 'O que é um bug em programação?', 'correct_answer' => 'erro', 'password' => 'debug', 'hint' => 'Um problema no código', 'explanation' => 'Um bug é um erro ou defeito no código.'],
    ['question_number' => 8, 'question' => 'Qual é a sobremesa tradicional de Páscoa?', 'correct_answer' => 'chocolate', 'password' => 'sweet', 'hint' => 'Começa com C, é feito de cacau', 'explanation' => 'O chocolate é a sobremesa tradicional de Páscoa.'],
    ['question_number' => 9, 'question' => 'Em programação, o que é uma variável?', 'correct_answer' => 'container', 'password' => 'memory', 'hint' => 'É um espaço na memória que guarda um valor', 'explanation' => 'Uma variável é um container de memória.'],
    ['question_number' => 10, 'question' => 'Quantos dias de jejum precedem o Domingo de Páscoa?', 'correct_answer' => '40', 'password' => 'faith', 'hint' => 'É um número importante na religião cristã', 'explanation' => 'A Quaresma dura 40 dias.'],
];

foreach ($questions as $q) {
    Question::create($q);
}

echo "✅ 10 perguntas criadas na base de dados!\n";
