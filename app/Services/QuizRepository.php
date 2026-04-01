<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class QuizRepository
{
    private const CORRECT_ANSWER_POINTS = 10;
    private const WRONG_MULTIPLE_CHOICE_PENALTY = -3;
    private const WRONG_DIRECT_ANSWER_PENALTY = -1;

    private $dataPath;

    public function __construct()
    {
        $this->dataPath = storage_path('app/quiz');
        if (!File::exists($this->dataPath)) {
            File::makeDirectory($this->dataPath, 0755, true);
        }
    }

    // ===== QUESTIONS =====
    public function getAllQuestions()
    {
        $file = $this->dataPath . '/questions.json';
        if (File::exists($file)) {
            return json_decode(File::get($file), true);
        }
        return $this->initializeQuestions();
    }

    private function initializeQuestions()
    {
        $questions = [
            [
                'id' => 1,
                'question_number' => 1,
                'question' => 'Em qual linguagem foi originalmente desenvolvido o Laravel?',
                'correct_answer' => 'PHP',
                'hint' => 'Começa com P... é uma linguagem de servidor web muito popular',
                'explanation' => 'Laravel é um framework de programação web escrito em PHP.',
                'password' => 'laravel'
            ],
            [
                'id' => 2,
                'question_number' => 2,
                'question' => 'Qual é o símbolo usado para representar a Páscoa?',
                'correct_answer' => 'ovo',
                'hint' => 'É um item redondo que coelhos entregam na Páscoa',
                'explanation' => 'O ovo é o símbolo principal da Páscoa, representando a vida nova.',
                'password' => 'easter'
            ],
            [
                'id' => 3,
                'question_number' => 3,
                'question' => 'Qual framework JavaScript é frequentemente usado para UI em programação?',
                'correct_answer' => 'React',
                'hint' => 'Desenvolvido pelo Facebook, começa com R',
                'explanation' => 'React é uma biblioteca JavaScript para construir interfaces.',
                'password' => 'react'
            ],
            [
                'id' => 4,
                'question_number' => 4,
                'question' => 'Qual festival cristão comemora a ressurreição de Jesus, associado à Páscoa?',
                'correct_answer' => 'Páscoa',
                'hint' => 'A resposta está na pergunta!',
                'explanation' => 'A Páscoa é o festival cristão que celebra a ressurreição de Jesus.',
                'password' => 'jesus'
            ],
            [
                'id' => 5,
                'question_number' => 5,
                'question' => 'Em programação, o que significa HTML?',
                'correct_answer' => 'HyperText Markup Language',
                'hint' => 'É a linguagem de marcação usada para criar páginas web',
                'explanation' => 'HTML é o padrão para criar documentos web.',
                'password' => 'html'
            ],
            [
                'id' => 6,
                'question_number' => 6,
                'question' => 'Qual é o animal tradicional associado à Páscoa?',
                'correct_answer' => 'coelho',
                'hint' => 'É um animal fofo com longas orelhas',
                'explanation' => 'O coelho de Páscoa é uma tradição moderna.',
                'password' => 'bunny'
            ],
            [
                'id' => 7,
                'question_number' => 7,
                'question' => 'O que é um "bug" em programação?',
                'correct_answer' => 'erro',
                'hint' => 'Um problema no código',
                'explanation' => 'Um bug é um erro ou defeito no código.',
                'password' => 'debug'
            ],
            [
                'id' => 8,
                'question_number' => 8,
                'question' => 'Qual é a sobremesa tradicional de Páscoa em muitos países?',
                'correct_answer' => 'chocolate',
                'hint' => 'Começa com C, é feito de cacau',
                'explanation' => 'O chocolate é a sobremesa tradicional de Páscoa.',
                'password' => 'sweet'
            ],
            [
                'id' => 9,
                'question_number' => 9,
                'question' => 'Em programação, o que é uma "variável"?',
                'correct_answer' => 'container',
                'hint' => 'É um espaço na memória que guarda um valor',
                'explanation' => 'Uma variável é um container de memória.',
                'password' => 'memory'
            ],
            [
                'id' => 10,
                'question_number' => 10,
                'question' => 'Quantos dias de jejum precedem o Domingo de Páscoa (Quaresma)?',
                'correct_answer' => '40',
                'hint' => 'É um número importante na religião cristã',
                'explanation' => 'A Quaresma dura 40 dias.',
                'password' => 'faith'
            ],
        ];

        File::put($this->dataPath . '/questions.json', json_encode($questions, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $questions;
    }

    public function getQuestion($id)
    {
        $questions = $this->getAllQuestions();
        return collect($questions)->firstWhere('id', $id);
    }

    // ===== TEAMS =====
    public function getAllTeams()
    {
        $file = $this->dataPath . '/teams.json';
        if (File::exists($file)) {
            return json_decode(File::get($file), true);
        }
        return [];
    }

    public function createTeam($name, $password = null)
    {
        $teams = $this->getAllTeams();
        $code = strtoupper(substr(uniqid(), -6));
        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#14b8a6'];
        $color = $colors[array_rand($colors)];

        // Se nenhuma password foi fornecida, gera uma aleatória
        $teamPassword = $password ?? strtoupper(substr(uniqid(), -4));

        $team = [
            'id' => count($teams) + 1,
            'name' => $name,
            'code' => $code,
            'color' => $color,
            'password' => $teamPassword,
            'correct_answers' => 0,
            'score' => 0,
            'created_at' => now()->toDateTimeString()
        ];

        $teams[] = $team;
        File::put($this->dataPath . '/teams.json', json_encode($teams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $team;
    }

    public function getTeamByName($name)
    {
        $teams = $this->getAllTeams();
        return collect($teams)->firstWhere('name', $name);
    }

    public function verifyTeamPassword($name, $password)
    {
        $team = $this->getTeamByName($name);
        if (!$team) {
            return false;
        }

        // Normalize password: trim and uppercase
        $normalizedPassword = strtoupper(trim($password));
        $storedPassword = strtoupper(trim($team['password']));

        return $normalizedPassword === $storedPassword;
    }

    public function getTeamByCode($code)
    {
        $teams = $this->getAllTeams();
        return collect($teams)->firstWhere('code', $code);
    }

    public function getTeamById($id)
    {
        $teams = $this->getAllTeams();
        return collect($teams)->firstWhere('id', $id);
    }

    // ===== ANSWERS =====
    public function getAllAnswers()
    {
        $file = $this->dataPath . '/answers.json';
        if (File::exists($file)) {
            return json_decode(File::get($file), true);
        }
        return [];
    }

    public function getTeamAnswers($teamId)
    {
        // Valida que a equipa realmente existe
        $team = $this->getTeamById($teamId);
        if (!$team) {
            return [];
        }

        // Filtra respostas apenas se a equipa foi criada antes das respostas
        $answers = $this->getAllAnswers();
        $teamCreatedAt = strtotime($team['created_at']);

        return collect($answers)
            ->where('team_id', $teamId)
            ->filter(function ($answer) use ($teamCreatedAt) {
                // Apenas inclui respostas criadas depois que a equipa foi criada
                $answerCreatedAt = strtotime($answer['created_at']);
                return $answerCreatedAt >= $teamCreatedAt;
            })
            ->toArray();
    }

    public function saveAnswer($teamId, $questionId, $userAnswer, $isCorrect, array $question)
    {
        $answers = $this->getAllAnswers();

        $existingAnswer = collect($answers)
            ->first(function ($answer) use ($teamId, $questionId) {
                return $answer['team_id'] == $teamId && $answer['question_id'] == $questionId;
            });

        if (($existingAnswer['is_correct'] ?? false) === true) {
            return true;
        }

        $scoreDelta = $this->calculateScoreDelta($isCorrect, $question['question_type'] ?? null);

        // Remove existing answer if any
        $answers = collect($answers)
            ->reject(function ($answer) use ($teamId, $questionId) {
                return $answer['team_id'] == $teamId && $answer['question_id'] == $questionId;
            })
            ->toArray();

        // Add new answer
        $answers[] = [
            'id' => count($answers) + 1,
            'team_id' => $teamId,
            'question_id' => $questionId,
            'user_answer' => $userAnswer,
            'is_correct' => $isCorrect,
            'created_at' => now()->toDateTimeString()
        ];

        File::put($this->dataPath . '/answers.json', json_encode($answers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Update team correct_answers count
        $teams = $this->getAllTeams();
        $teamIndex = array_search($teamId, array_column($teams, 'id'));
        if ($teamIndex !== false) {
            $correctCount = collect($answers)
                ->where('team_id', $teamId)
                ->where('is_correct', true)
                ->count();
            $teams[$teamIndex]['correct_answers'] = $correctCount;
            $teams[$teamIndex]['score'] = ($teams[$teamIndex]['score'] ?? 0) + $scoreDelta;
            File::put($this->dataPath . '/teams.json', json_encode($teams, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        return true;
    }

    public function getTeamsProgress()
    {
        $teams = $this->getAllTeams();
        $answers = $this->getAllAnswers();

        return collect($teams)
            ->map(function ($team) use ($answers) {
                $correctAnswers = collect($answers)
                    ->where('team_id', $team['id'])
                    ->where('is_correct', true)
                    ->count();

                return [
                    'id' => $team['id'],
                    'name' => $team['name'],
                    'code' => $team['code'],
                    'color' => $team['color'],
                    'score' => $team['score'] ?? 0,
                    'correct_answers' => $correctAnswers,
                    'total_questions' => 10,
                    'percentage' => round(($correctAnswers / 10) * 100, 1),
                ];
            })
            ->sortByDesc('score')
            ->values()
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
