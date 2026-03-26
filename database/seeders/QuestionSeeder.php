<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Question;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question_number' => 1,
                'question' => 'Em qual linguagem foi originalmente desenvolvido o Laravel?',
                'correct_answer' => 'PHP',
                'hint' => 'Começa com P... é uma linguagem de servidor web muito popular',
                'explanation' => 'Laravel é um framework de programação web escrito em PHP. PHP é uma dos linguagens mais usadas na web.'
            ],
            [
                'question_number' => 2,
                'question' => 'Qual é o símbolo usado para representar a Páscoa?',
                'correct_answer' => 'ovo',
                'hint' => 'É um item redondo que coelhos entregam na Páscoa',
                'explanation' => 'O ovo é o símbolo principal da Páscoa, representando a vida nova e renovação.'
            ],
            [
                'question_number' => 3,
                'question' => 'Qual framework JavaScript é frequentemente usado para UI em programação?',
                'correct_answer' => 'React',
                'hint' => 'Desenvolvido pelo Facebook, começa com R',
                'explanation' => 'React é uma biblioteca JavaScript para construir interfaces de utilizador com componentes reutilizáveis.'
            ],
            [
                'question_number' => 4,
                'question' => 'Qual festival cristão comemora a ressurreição de Jesus, associado à Páscoa?',
                'correct_answer' => 'Páscoa',
                'hint' => 'A resposta está na pergunta! É o festival mais importante do cristianismo',
                'explanation' => 'A Páscoa é o festival cristão que celebra a ressurreição de Jesus Cristo.'
            ],
            [
                'question_number' => 5,
                'question' => 'Em programação, o que significa HTML?',
                'correct_answer' => 'HyperText Markup Language',
                'hint' => 'É a linguagem de marcação usada para criar páginas web',
                'explanation' => 'HTML (HyperText Markup Language) é o padrão para criar documentos web com tags e elementos.'
            ],
            [
                'question_number' => 6,
                'question' => 'Qual é o animal tradicional associado à Páscoa?',
                'correct_answer' => 'coelho',
                'hint' => 'É um animal fofo com longas orelhas que entrega ovos na Páscoa',
                'explanation' => 'O coelho de Páscoa é uma tradição moderna que representa a fertilidade e renovação.'
            ],
            [
                'question_number' => 7,
                'question' => 'O que é um "bug" em programação?',
                'correct_answer' => 'erro',
                'hint' => 'Um problema no código que causa comportamento inesperado',
                'explanation' => 'Um bug é um erro ou defeito no código que causa comportamento inesperado no programa.'
            ],
            [
                'question_number' => 8,
                'question' => 'Qual é a sobremesa tradicional de Páscoa em muitos países?',
                'correct_answer' => 'chocolate',
                'hint' => 'Começa com C, é feito de cacau e muito delicioso',
                'explanation' => 'O chocolate é a sobremesa tradicional de Páscoa, especialmente ovos de chocolate.'
            ],
            [
                'question_number' => 9,
                'question' => 'Em programação, o que é uma "variável"?',
                'correct_answer' => 'container',
                'hint' => 'É um espaço na memória que guarda um valor',
                'explanation' => 'Uma variável é um contentor ou espaço de memória que guarda valores que podem mudar durante a execução do programa.'
            ],
            [
                'question_number' => 10,
                'question' => 'Quantos dias de jejum precedem o Domingo de Páscoa (Quaresma)?',
                'correct_answer' => '40',
                'hint' => 'É um número importante na religião cristã',
                'explanation' => 'A Quaresma dura 40 dias e é um período de preparação espiritual antes da Páscoa.'
            ],
        ];

        foreach ($questions as $question) {
            Question::create($question);
        }
    }
}
