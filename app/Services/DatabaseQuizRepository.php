<?php

namespace App\Services;

use App\Models\Team;
use App\Models\Question;
use App\Models\Answer;

class DatabaseQuizRepository
{
    private const CORRECT_ANSWER_POINTS = 10;
    private const WRONG_MULTIPLE_CHOICE_PENALTY = -3;
    private const WRONG_DIRECT_ANSWER_PENALTY = -1;

    // ===== QUESTIONS =====
    public function getAllQuestions()
    {
        $questions = Question::all()->toArray();
        if (empty($questions)) {
            return $this->initializeQuestions();
        }
        return $questions;
    }

    public function getQuestion($id)
    {
        return Question::find($id)?->toArray();
    }

    private function initializeQuestions()
    {
        $questions = [
            [
                'question_number' => 1,
                'question' => 'O que significa a sigla HTML?',
                'correct_answer' => 'HyperText Markup Language',
                'password' => 'cesae',
                'hint' => 'A linguagem padrão para criar páginas web',
                'explanation' => 'HTML é HyperText Markup Language, a linguagem fundamental da web.',
                'binary_code' => '01110000',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Hyper Tool Multi Language',
                    'b' => 'Home Tool Markup Language',
                    'c' => 'HyperText Markup Language',
                    'd' => 'HyperLinks and Text Markup Language'
                ]
            ],
            [
                'question_number' => 2,
                'question' => 'Qual é a função principal de um sistema operativo?',
                'correct_answer' => 'Gerir os recursos de hardware e software do computador',
                'password' => 'cesae',
                'hint' => 'Gerencia todos os recursos do seu computador',
                'explanation' => 'O sistema operativo é responsável por gerir hardware e software.',
                'binary_code' => '01100001',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Gerir os recursos de hardware e software do computador',
                    'b' => 'Criar documentos de texto e folhas de cálculo',
                    'c' => 'Proteger o computador contra vírus físicos no hardware',
                    'd' => 'Navegar na internet de forma anónima'
                ]
            ],
            [
                'question_number' => 3,
                'question' => 'Qual destas é uma linguagem de programação cujo nome é partilhado com uma cobra?',
                'correct_answer' => 'Python',
                'password' => 'cesae',
                'hint' => 'Um nome de um animal que é também uma serpente',
                'explanation' => 'Python é uma linguagem de programação muito popular que tem o nome de uma cobra.',
                'binary_code' => '01110011',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Cobra',
                    'b' => 'Anaconda',
                    'c' => 'Python',
                    'd' => 'Viper'
                ]
            ],
            [
                'question_number' => 4,
                'question' => 'Qual é a unidade básica de informação num computador?',
                'correct_answer' => 'Bit',
                'password' => 'cesae',
                'hint' => 'A unidade mais pequena que um computador pode processar',
                'explanation' => 'O bit (binary digit) é a unidade básica de informação digital.',
                'binary_code' => '01100011',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Byte',
                    'b' => 'Kilobyte',
                    'c' => 'Bit',
                    'd' => 'Nibble'
                ]
            ],
            [
                'question_number' => 5,
                'question' => 'Quantos bits compõem um byte?',
                'correct_answer' => '8',
                'password' => 'cesae',
                'hint' => 'Um byte tem sempre a mesma quantidade de bits.',
                'explanation' => 'Um byte é composto por 8 bits.',
                'binary_code' => '01101111',
            ],
            [
                'question_number' => 6,
                'question' => 'Em qual linguagem foi originalmente desenvolvido o Laravel?',
                'correct_answer' => 'php',
                'password' => 'cesae',
                'hint' => 'Uma linguagem de servidor web muito popular',
                'explanation' => 'Laravel é um framework desenvolvido em PHP.',
                'binary_code' => '01100001',
            ],
            [
                'question_number' => 7,
                'question' => 'Qual dos seguintes protocolos é utilizado para enviar emails?',
                'correct_answer' => 'SMTP',
                'password' => 'cesae',
                'hint' => 'O protocolo padrão para enviar correios eletrónicos',
                'explanation' => 'SMTP (Simple Mail Transfer Protocol) é usado para enviar emails.',
                'binary_code' => '01100011',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'FTP',
                    'b' => 'HTTP',
                    'c' => 'SMTP',
                    'd' => 'DNS'
                ]
            ],
            [
                'question_number' => 8,
                'question' => 'Em programação orientada a objetos, como se chama o mecanismo que permite uma classe herdar características de outra?',
                'correct_answer' => 'Herança',
                'password' => 'cesae',
                'hint' => 'Uma classe pode herdar propriedades de outra classe',
                'explanation' => 'Herança (Inheritance) permite que uma classe reutilize código de outra.',
                'binary_code' => '01100101',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Encapsulamento',
                    'b' => 'Polimorfismo',
                    'c' => 'Herança',
                    'd' => 'Abstração'
                ]
            ],
            [
                'question_number' => 9,
                'question' => 'Qual a linguagem padrão para consultar e gerir bases de dados relacionais?',
                'correct_answer' => 'SQL',
                'password' => 'cesae',
                'hint' => 'A linguagem usada para consultar bases de dados',
                'explanation' => 'SQL (Structured Query Language) é a linguagem padrão para bases de dados.',
                'binary_code' => '01110011',
            ],
            [
                'question_number' => 10,
                'question' => 'Qual é a porta padrão para o protocolo SSH?',
                'correct_answer' => '22',
                'password' => 'cesae',
                'hint' => 'Um número de porta de rede',
                'explanation' => 'A porta 22 é a porta padrão para SSH (Secure Shell).',
                'binary_code' => '01100001',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => '80',
                    'b' => '443',
                    'c' => '22',
                    'd' => '21'
                ]
            ],
            [
                'question_number' => 11,
                'question' => 'Qual é a linguagem de estilo usada para formatar páginas web?',
                'correct_answer' => 'Cascading Style Sheets',
                'password' => 'cesae',
                'hint' => 'Utilizada para formatar HTML nas páginas web',
                'explanation' => 'CSS (Cascading Style Sheets) é usada para estilizar páginas web.',
                'binary_code' => '01100101',
                'question_type' => 'multiple_choice',
                'options' => [
                    'a' => 'Cascading Style Sheets',
                    'b' => 'Chaos, Stress, Suffering',
                    'c' => 'Cascading Server States',
                    'd' => 'Command Shell Snippets'
                ]
            ],
        ];

        foreach ($questions as $q) {
            Question::create($q);
        }

        return Question::all()->toArray();
    }

    // ===== TEAMS =====
    public function getAllTeams()
    {
        return Team::all()->map(function ($team) {
            return $team->toArray();
        })->toArray();
    }

    public function getTeamByName($name)
    {
        $team = Team::where('name', $name)->first();
        return $team ? $team->toArray() : null;
    }

    public function getTeamById($id)
    {
        $team = Team::find($id);
        return $team ? $team->toArray() : null;
    }

    public function getTeamByCode($code)
    {
        $team = Team::where('code', $code)->first();
        return $team ? $team->toArray() : null;
    }

    public function createTeam($name, $password)
    {
        $code = strtoupper(substr(uniqid(), -6));
        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#14b8a6'];
        $color = $colors[array_rand($colors)];

        $teamPassword = strtoupper(trim($password));

        $team = Team::create([
            'name' => $name,
            'code' => $code,
            'color' => $color,
            'password' => $teamPassword,
            'correct_answers' => 0,
            'score' => 0,
            'is_winner' => false,
        ]);

        return $team->toArray();
    }

    public function verifyTeamPassword($name, $password)
    {
        $team = Team::where('name', $name)->first();
        if (!$team) {
            return false;
        }

        $normalizedPassword = strtoupper(trim($password));
        $storedPassword = strtoupper(trim($team->password));

        return $normalizedPassword === $storedPassword;
    }

    // ===== ANSWERS =====
    public function getTeamAnswers($teamId)
    {
        $team = Team::find($teamId);
        if (!$team) {
            return [];
        }

        return Answer::where('team_id', $teamId)
            ->get()
            ->map(function ($answer) {
                return $answer->toArray();
            })
            ->toArray();
    }

    public function saveAnswer($teamId, $questionId, $userAnswer, $isCorrect, array $question)
    {
        $team = Team::find($teamId);
        if (!$team) {
            return [
                'score_delta' => 0,
                'score' => 0,
                'already_solved' => false,
            ];
        }

        $existingAnswer = Answer::where('team_id', $teamId)
            ->where('question_id', $questionId)
            ->first();

        if ($existingAnswer && $existingAnswer->is_correct) {
            return [
                'score_delta' => 0,
                'score' => (int) $team->score,
                'already_solved' => true,
            ];
        }

        $scoreDelta = $this->calculateScoreDelta($isCorrect, $question['question_type'] ?? null);

        // Remove existing answer if any
        Answer::where('team_id', $teamId)
            ->where('question_id', $questionId)
            ->delete();

        // Add new answer
        Answer::create([
            'team_id' => $teamId,
            'question_id' => $questionId,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
        ]);

        // Update team correct_answers count
        $correctCount = Answer::where('team_id', $teamId)
            ->where('is_correct', true)
            ->count();

        $team->update([
            'correct_answers' => $correctCount,
            'score' => $team->score + $scoreDelta,
        ]);

        $team->refresh();

        return [
            'score_delta' => $scoreDelta,
            'score' => (int) $team->score,
            'already_solved' => false,
        ];
    }

    public function getTeamsProgress()
    {
        return Team::with('answers')
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                    'code' => $team->code,
                    'color' => $team->color,
                    'score' => $team->score,
                    'correct_answers' => $team->correct_answers,
                    'is_winner' => (bool) $team->is_winner,
                ];
            })
            ->toArray();
    }

    private function calculateScoreDelta(bool $isCorrect, ?string $questionType): int
    {
        if ($isCorrect) {
            return self::CORRECT_ANSWER_POINTS;
        }

        return $questionType === 'multiple_choice'
            ? self::WRONG_MULTIPLE_CHOICE_PENALTY
            : self::WRONG_DIRECT_ANSWER_PENALTY;
    }
}
